<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $helpers = array('Html', 'Form');

    public $uses = array('User');

    public function beforeFilter() {
        $this->Auth->allow('register', 'create', 'login');
    }

    public function register() {
        if ($this->request->is('post')) {
            $userData = $this->request->data;

            $this->User->set($userData);

            if (!$this->User->validates()) {
                //do nothing

            } else {

                if ($this->User->save()) {
                    $this->Flash->success('User registration successful.');
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                } else {
                    $this->Flash->error('User registration failed.');
                    $this->redirect($this->request->referer());
                }
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
                $existingUser['User']['password'] = $userData['new_password'];
            } else {
                unset($existingUser['User']['password']);
            }
    
            $this->User->set($existingUser);
    
            if ($this->User->validates()) {
                if ($this->User->save($existingUser)) {
                    $this->Flash->success('User profile updated successfully.');
                    $this->redirect(array('controller' => 'userprofiles', 'action' => 'show'));
                } else {
                    $this->Flash->error('Failed to update user profile.');
                }
            } else {
                $errors = $this->User->validationErrors;
                $this->Flash->error('Validation failed: ' . json_encode($errors));
            }
        }
    }
    

    public function login() {
        $this->loadModel('Userprofile');
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->afterLogin();

                $loggedInUserId = $this->Auth->user('id');
                $hasUserProfile = $this->Userprofile->find('count', [
                    'conditions' => ['user_id' => $loggedInUserId],
                ]);
    
                if ($hasUserProfile) {
                    return $this->redirect(['controller' => 'userprofiles', 'action' => 'show']);
                } else {
                    return $this->redirect(['controller' => 'userprofiles', 'action' => 'new']);
                }
            } else {
                $this->Flash->error(__('Invalid email or password, try again'));
            }
        }
    }
    

    public function logout() {
        $this->redirect($this->Auth->logout());
    }
}
