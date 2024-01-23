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
                    $this->redirect(array('controller' => 'userprofiles', 'action' => 'show'));
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

    public function show() {
        $userProfile = $this->setUserProfile();
        $this->set('userProfile', $userProfile);
    }

    public function edit() {
        $userProfile = $this->setUserProfile();
        $this->set('userProfile', $userProfile);
    }

    public function update() {
        var_dump($this->request->data);
        if ($this->request->is('post')) {
            $this->User->begin();
    
            try {
                $userData = $this->request->data['User'];
                $userProfileData = $this->request->data['UserProfile'];
    
                $userId = $this->Auth->user('id');
                $existingUserProfile = $this->UserProfile->findByUserId($userId);

    
                $userProfileData['id'] = $existingUserProfile['UserProfile']['id'];
                if ($this->UserProfile->save($userProfileData)) {
                    $this->User->id = $userId;
                    if ($this->User->save($userData)) {
                        $this->User->commit();
                        $this->Flash->success('Profile updated successfully.');
                    } else {
                        $this->User->rollback();
                        $this->Flash->error('Failed to update user data.');
                    }
                } else {
                    $this->User->rollback();
                    $this->Flash->error('Failed to update user profile data.');
                }
            } catch (Exception $e) {
                $this->User->rollback();
                $this->Flash->error('An error occurred during the update process.');
            }
    
            $this->redirect(array('controller' => 'userprofiles', 'action' => 'show'));
        }
    }
    
    

    private function setUserName() {
        $userId = $this->Auth->user('id');
        $userData = $this->User->findById($userId);
        return isset($userData['User']['name']) ? $userData['User']['name'] : '';
    }

    private function setUserProfile() {
        $userId = $this->Auth->user('id');
        $userProfileData = $this->UserProfile->find('first', array(
            'conditions' => array('UserProfile.user_id' => $userId)
        ));
        return $userProfileData;
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


