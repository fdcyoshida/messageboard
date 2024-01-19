<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $helpers = array('Html', 'Form');

    public function register() {
    }

    public function create() {
        if ($this->request->is('post')) {
            $userData = $this->request->data;

            if ($this->User->save($userData)) {
                $this->Session->setFlash('User registration successful.');
                $this->redirect($this->request->referer());
            } else {
                $this->Session->setFlash('User registration failed.');
                $this->redirect($this->request->referer());
            }
        }
    }

}
