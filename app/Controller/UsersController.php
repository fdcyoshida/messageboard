<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $helpers = array('Html', 'Form');

    public $uses = array('User');

    public function beforeFilter() {
        $this->Auth->allow('register', 'create', 'login');
    }

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

    public function edit () {
        $user = $this->Auth->user();
        $this->set('user', $user);
    }
        
    public function update() {
        if ($this->request->is('post')) {
            $userData = $this->request->data['User'];

            $userId = $this->Auth->user('id');

            $existingUser = $this->User->findById($userId);

            if (!empty($userData['email'])) {
                $existingUser['User']['email'] = $userData['email'];
            }
            
            if (!empty($userData['new_password']) && !empty($userData['confirm_password']) && $userData['new_password'] === $userData['confirm_password']) {
                $existingUser['User']['password'] =$userData['new_password'];

            }

            if ($this->User->save($existingUser)) {
                $this->Flash->success('User profile updated successfully.');
                $this->redirect(array('controller' => 'userprofiles', 'action' => 'show'));
            } else {
                $this->Flash->error('Failed to update user profile.');
            }
        }
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            } else {
                $this->Flash->error(__('Invalid email or password, try again'));
            }
        }
    }

    public function logout() {
        $this->redirect($this->Auth->logout());
    }
}
