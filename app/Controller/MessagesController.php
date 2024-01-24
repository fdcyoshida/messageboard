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
            $data['Message']['created_at'] = date('Y-m-d H:i:s');

            if ($this->Message->save($data)) {
                $this->Flash->success('the message has been sent.');
            } else {
                $this->Flash->error('Failed to send message.');
            }
        }
    }
    
}
