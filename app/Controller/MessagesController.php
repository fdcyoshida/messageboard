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
                        'UserProfile.user_id = Sender.id',
                    ],
                ]
            ],
            'fields' => 'UserProfile.img, Sender.name, Sender.id, Message.text, Message.created, Message.sender_id, Message.receiver_id',
        ]);
    
        $latestMessages = $this->getLatestMessagesInGroups($latestMessages);
        $latestMessages = $this->filterDuplicateCombinations($latestMessages);
        $this->set('latestMessages', $latestMessages);
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
            $senderId = $messageGroup['Sender']['id'];
            if (!isset($groupedMessages[$senderId]) || $messageGroup['Message']['created'] > $groupedMessages[$senderId]['Message']['created']) {
                $groupedMessages[$senderId] = $messageGroup;
            }
        }
    
        return array_values($groupedMessages);
    }

    private function filterDuplicateCombinations($latestMessages) {
        $filteredMessages = [];

        foreach ($latestMessages as $message) {
            $senderId = $message['Sender']['id'];
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
