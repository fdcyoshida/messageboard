<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController {
    public function send() {
        if ($this->request->is('post')) {
            $data = $this->request->data;

            if ($this->Message->save($data)) {
                $this->Flash->success('the message has been sent.');
            } else {
                $this->Flash->error('Failed to send message.');
            }
        }
    }
}