<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $helpers = array('Html', 'Form');

    public $uses = array('User');

    public function register() {
    }

    public function create() {
        if ($this->request->is('post')) {
            $userData = $this->request->data;

            if ($this->User->save($userData)) {
                $this->Flash->success('User registration successful.');
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            } else {
                $this->Flash->error('User registration failed.');
                $this->redirect($this->request->referer());
            }
        }
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
            }
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }    
}
