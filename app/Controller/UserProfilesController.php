<?php
App::uses('AppController', 'Controller');

class UserProfilesController extends AppController {
    public $helpers = array('Html', 'Form');
    public $uses = array('UserProfile', 'User');

    public function new() {
        $this->set('userName', $this->setUserName());
    
        if ($this->request->is('post')) {
            $this->loadModel('User');
            $userId = $this->Auth->user('id');
            $birthday = date('Y-m-d', strtotime($this->request->data['UserProfile']['birthday']));


            try {
                $this->User->begin();
    
                $image = $this->handleImageUpload();
    
                $userData = array(
                    'User' => array(
                        'id' => $userId,
                        'name' => $this->request->data['User']['name']
                    )
                );
                $profileData = array(
                    'UserProfile' => array(
                        'user_id' => $userId,
                        'img' => $image,
                        'gender' => $this->request->data['UserProfile']['gender'], 
                        'birthday' => $birthday,
                        'hobby' => $this->request->data['UserProfile']['hobby']
                    )
                );
    
                $this->User->set($userData);
                if ($this->User->validates()) {
    
                    $this->UserProfile->set($profileData);
                    if ($this->UserProfile->validates()) {
                        if ($this->User->saveAssociated($userData, array('deep' => true, 'atomic' => false))) {
                            $this->User->commit();
                            $this->UserProfile->save($profileData);
                            $this->Flash->success('Profile created successfully.');
                            $this->log($profileData, 'debug');

                            $this->redirect(array('controller' => 'userprofiles', 'action' => 'show'));
                        } else {
                            $this->User->rollback();
                            $this->Flash->error('Failed to create profile.');
                        }
                    } else {
                        $validationErrors = $this->UserProfile->validationErrors;
                        
                        if (isset($validationErrors['img']) && is_array($validationErrors['img'])) {
                            foreach ($validationErrors['img'] as $error) {
                                $this->Flash->error($error);
                            }
                        } else {
                            //do nothing
                        }
                    }
    
                } else {
                    //do nothing
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
        $userId = $this->Auth->user('id');
        if ($this->request->is('post')) {
            $this->User->begin();
            $userData = $this->request->data['User'];
            $userProfileData = $this->request->data['UserProfile'];
            $userProfileData['id'] = $userProfile['UserProfile']['id'];
            $birthday = date('Y-m-d', strtotime($this->request->data['UserProfile']['birthday']));
            
            $userProfileData = array(
                'UserProfile' => array(
                    'id' => $userProfile['UserProfile']['id'],
                    'user_id' => $userId,
                    'gender' => $this->request->data['UserProfile']['gender'], 
                    'birthday' =>  $birthday,
                    'hobby' => $this->request->data['UserProfile']['hobby']
                )
            );
            $this->User->set($userData);
            $this->UserProfile->set($userProfileData);

            if ($this->User->validates() && $this->UserProfile->validates()) {
                try {
                    if (!empty($this->request->data['UserProfile']['image']['tmp_name'])) {
                        $newImage = $this->handleImageUpload();
                        $userProfileData['img'] = $newImage;
                    } else {
                        unset($userProfileData['img']);
                    }
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
                    CakeLog::write('error', 'An error occurred during the update process: ' . $e->getMessage());
                    $this->Flash->error('An error occurred during the update process.');
                }
    
                $this->redirect(array('controller' => 'userprofiles', 'action' => 'show'));
            } else {
                $this->Flash->error('Validation failed. Please check your input.');
            }
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
            return $filename;
        }

        return null;
    }
}


