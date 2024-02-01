<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController {
    public $components = array('Paginator');

    public function list() {
        $loggedInUserId = $this->Auth->user('id');
    
        $latestMessages = $this->Message->find('all', [
            'conditions' => [
                'OR' => [
                    'Message.receiver_id' => $loggedInUserId,
                    'Message.sender_id' => $loggedInUserId,
                ]
            ],
            'order' => ['Message.created' => 'DESC'],
            'joins' => [
                [
                    'type' => 'left',
                    'table' => 'userprofiles',
                    'alias' => 'UserProfile',
                    'conditions' => [
                        'UserProfile.user_id = Message.sender_id',
                    ],
                ]
            ],
            'fields' => [
                        'UserProfile.img',
                        'Sender.name',
                        'Message.text',
                        'Message.created',
                        'Message.sender_id',
                        'Message.receiver_id',
            ]
        ]);

        $latestMessages = $this->getLatestMessagesInGroups($latestMessages);
        $latestMessages = $this->filterDuplicateCombinations($latestMessages);
        
        usort($latestMessages, function($a, $b) {
            return strtotime($b['Message']['created']) - strtotime($a['Message']['created']);
        });
        
        $this->set('latestMessages', $latestMessages);
    }

    public function new() {
    }

    public function send() {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $loggedInUserId = $this->Auth->user('id');
            $data['Message']['sender_id'] = $loggedInUserId;
    
            $this->Message->set($data);
            if ($this->Message->validates()) {
                if ($this->Message->save($data)) {
                    $this->Flash->success('The message has been sent.');
                    $this->redirect(array('controller' => 'messages', 'action' => 'list'));
                } else {
                    $this->Flash->error('Failed to send message.');
                    return $this->redirect(['controller' => 'messages', 'action' => 'new']);
                }
            } else {
                $this->Flash->error('Validation failed. Please check your input.');
                return $this->redirect(['controller' => 'messages', 'action' => 'new']);
            }
        }
    }

    public function detail() {
        $loggedInUserId = $this->Auth->user('id');
        $getQueryParams = $this->request->query;
        $firstUserId = $getQueryParams['first_user_id'];
        $secondUserId = $getQueryParams['second_user_id'];

        $this->Paginator->settings = [
            'limit' => 10,
            'order' => ['Message.created' => 'desc'],
            'conditions' => [
                'OR' => [
                    ['sender_id' => $firstUserId, 'receiver_id' => $secondUserId],
                    ['sender_id' => $secondUserId, 'receiver_id' => $firstUserId],
                ],
            ],
            'joins' => [
                [
                    'table' => 'userprofiles',
                    'alias' => 'SenderProfile',
                    'type' => 'LEFT',
                    'conditions' => [
                        'SenderProfile.user_id = Message.sender_id',
                    ],
                ],
            ],
            'fields' => [
                'SenderProfile.img AS sender_img',
                'Sender.name AS sender_name',
                'Sender.id',
                'Message.id',
                'Message.text',
                'Message.created',
                'Message.sender_id',
                'Message.receiver_id'
            ],
        ];

        $messages = $this->Paginator->paginate('Message');
        $this->set('messages', $messages);
        $this->set('loggedInUserId', $loggedInUserId);
    }

    public function reply() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $postData = $this->request->data['Message'];
            $loggedInUserId = $this->Auth->user('id');
    
            $loggedInUser = $this->Message->Sender->findById($loggedInUserId);
            $loggedInUserProfile = $this->Message->Sender->UserProfile->findByUserId($loggedInUserId);
    
            $replyData = [
                'sender_id' => $loggedInUserId,
                'receiver_id' => ($loggedInUserId == $postData['first_user_id']) ? $postData['second_user_id'] : $postData['first_user_id'],
                'text' => $postData['text'],
                'sender_name' => $loggedInUser['Sender']['name'],
                'sender_img' => $loggedInUserProfile['UserProfile']['img'],
                'created' => date('Y-m-d H:i:s'), 
            ];
    
            $this->Message->save($replyData);
    
            $this->response->type('json');
            $this->response->body(json_encode(['success' => true, 'replyMessage' => $replyData]));
            return $this->response;
        } else {
            $this->response->type('json');
            $this->response->body(json_encode(['success' => false, 'message' => 'Invalid request']));
            return $this->response;
        }
    }
    
    
    

    
    public function destroyConversation() {
        if ($this->request->is('post')) {
            $postData = $this->request->data[Message];
    
            $firstUserId = $postData['first_user_id'];
            $secondUserId = $postData['second_user_id'];
    
            $this->Message->deleteAll(
                [
                    'OR' => [
                        [
                            'sender_id' => $firstUserId,
                            'receiver_id' => $secondUserId,
                        ],
                        [
                            'sender_id' => $secondUserId,
                            'receiver_id' => $firstUserId,
                        ],
                    ],
                ]
            );
    
            $this->Flash->success('Conversation destroyed successfully.');
    
            return $this->redirect(['action' => 'list']);
        }
    }
    
    public function destroyMessage() {
        $this->autoRender = false;
        if ($this->request->is('ajax') && $this->request->is('post')) {
            $id = $this->request->data['messageId'];
    
            $message = $this->Message->findById($id);
    
            if ($message) {
                $this->Message->delete($id);
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Message not found'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Invalid request'];
        }
    
        $this->response->type('json');
        $this->response->body(json_encode($response));
        return $this->response;
    }
    
    

    public function getUsers() {
        $this->autoRender = false;
        $this->loadModel('User');
    
        if ($this->request->is('ajax')) {
            $name = $this->request->query('q');
            $users = $this->User->find('all', ['conditions' => ['name LIKE' => '%' . $name . '%']]);
    
            $usersArray = [];
            foreach ($users as $user) {
                if (is_array($user)) {
                    $usersArray[] = ['id' => $user['User']['id'], 'name' => $user['User']['name']];
                } elseif (is_object($user)) {
                    $usersArray[] = ['id' => $user->id, 'name' => $user->name];
                }
            }
    
            echo json_encode(['users' => $usersArray]);
            exit();
        }
    }
    
    
    
    private function getLatestMessagesInGroups($latestMessages) {
        $groupedMessages = [];
        foreach ($latestMessages as $messageGroup) {
            $senderId = $messageGroup['Message']['sender_id'];
            $receiverId = $messageGroup['Message']['receiver_id'];
    
            $key = $senderId . '_' . $receiverId;
    
            if (!isset($groupedMessages[$key]) || $messageGroup['Message']['created'] > $groupedMessages[$key]['Message']['created']) {
                $groupedMessages[$key] = $messageGroup;
            }
        }
    
        return array_values($groupedMessages);
    }

    private function filterDuplicateCombinations($latestMessages) {
        $filteredMessages = [];

        foreach ($latestMessages as $message) {
            $senderId = $message['Message']['sender_id'];
            $receiverId = $message['Message']['receiver_id'];
    
            $reverseCombinationExists = isset($filteredMessages[$receiverId][$senderId]);
    
            if (!$reverseCombinationExists || $message['Message']['created'] > $filteredMessages[$receiverId][$senderId]['Message']['created']) {
                $filteredMessages[$senderId][$receiverId] = $message;
            }
        }
    
        $filteredMessages = array_merge(...array_values($filteredMessages));
    
        return $filteredMessages;
    }
}