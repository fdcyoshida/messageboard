<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController {

    public function new() {
        $loggedInUserId = $this->Auth->user('id');

        $this->loadModel('User'); 

        $users = $this->User->find('list', [
            'conditions' => ['User.id NOT' => $loggedInUserId],
            'contain' => ['UserProfile'],
        ]);
        $this->set('users', $users);
    }

    public function send() {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $loggedInUserId = $this->Auth->user('id');
            $data['Message']['sender_id'] = $loggedInUserId;

            if ($this->Message->save($data)) {
                $this->Flash->success('the message has been sent.');
                $this->redirect(array('controller' => 'messages', 'action' => 'list'));
            } else {
                $this->Flash->error('Failed to send message.');
            }
        }
    }
    
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
            'fields' => 'UserProfile.img, Sender.name, Message.text, Message.created, Message.sender_id, Message.receiver_id',
        ]);

        $latestMessages = $this->getLatestMessagesInGroups($latestMessages);
        $latestMessages = $this->filterDuplicateCombinations($latestMessages);
        
        usort($latestMessages, function($a, $b) {
            return strtotime($b['Message']['created']) - strtotime($a['Message']['created']);
        });
        
        $this->set('latestMessages', $latestMessages);
        
    }

    public function detail() {

        $postData = $this->request->data['Message'];
        $firstUserId = $postData['first_user_id'];
        $secondUserId = $postData['second_user_id'];

        $messages = $this->Message->find('all', [
            'conditions' => [
                'OR' => [
                    ['sender_id' => $firstUserId, 'receiver_id' => $secondUserId],
                    ['sender_id' => $secondUserId, 'receiver_id' => $firstUserId],
                ],
            ],
            'order' => ['Message.created' => 'DESC'],
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
                'Message.text',
                'Message.created',
            ],
        ]);
        
        
        $this->set('messages', $messages);
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