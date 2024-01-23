<?php
App::uses('AppController', 'Controller');

class UserProfilesController extends AppController {
    public $helpers = array('Html', 'Form');
    public $uses = array('UserProfile', 'User');

    public function new() {
        $this->set('userName', $this->setUserName());
    }

    public function create() {
        if ($this->request->is('post')) {
            $userId = $this->Auth->user('id');

            $image = $this->handleImageUpload();
            $this->request->data['UserProfile']['user_id'] = $userId;
            $this->request->data['UserProfile']['img'] = $image;

            $this->request->data['User']['id'] = $userId;
            $this->request->data['User']['name'] = $this->request->data['User']['name'];

            $this->User->begin();

            try {
                if ($this->UserProfile->save($this->request->data['UserProfile']) &&
                    $this->User->save($this->request->data['User'])) {

                    $this->User->commit();
                    $this->Flash->success('Profile created successfully.');
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                } else {
                    $this->User->rollback();
                    $this->Flash->error('Failed to create profile.');
                }
            } catch (Exception $e) {
                $this->User->rollback();
                $this->Flash->error('Failed to create profile.');
            }
        }
    }

    private function setUserName() {
        $userId = $this->Auth->user('id');
        $userData = $this->User->findById($userId);
        return isset($userData['User']['name']) ? $userData['User']['name'] : '';
    }

    private function handleImageUpload() {
        $file = $this->request->data['UserProfile']['image'];
        $uploadPath = WWW_ROOT . 'img/uploads/';
        $filename = uniqid() . '_' . $file['name'];

        if (move_uploaded_file($file['tmp_name'], $uploadPath . $filename)) {
            return 'uploads/' . $filename;
        }

        return null;
    }
}

