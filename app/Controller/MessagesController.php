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
            'conditions' => ['Message.receiver_id' => $loggedInUserId],
            'joins' => [
                [
                    'type' => 'left',
                    'table' => 'userprofiles',
                    'alias' => 'UserProfile',
                    'conditions' => 'UserProfile.user_id = Sender.id'
                ]
            ],
            'order' => ['Message.created' => 'DESC'],
            'fields' => 'UserProfile.img, Sender.name, Sender.id, Message.text, Message.created',
        ]);
    
        $latestMessages = $this->getLatestMessagesInGroups($latestMessages);
    
        $this->set('latestMessages', $latestMessages);
    }
    
    protected function getLatestMessagesInGroups($latestMessages) {
        $groupedMessages = [];
        foreach ($latestMessages as $messageGroup) {
            $senderId = $messageGroup['Sender']['id'];
            if (!isset($groupedMessages[$senderId]) || $messageGroup['Message']['created'] > $groupedMessages[$senderId]['Message']['created']) {
                $groupedMessages[$senderId] = $messageGroup;
            }
        }
    
        return array_values($groupedMessages);
    }
    
}
