<?php
App::uses('AppController', 'Controller');

class UserProfilesController extends AppController {
    public $helpers = array('Html', 'Form');
    public $uses = array('UserProfile', 'User');

    public function new() {
    }

    public function create() {
        $id = $this->Auth->user('id');
        if ($this->request->is('post')) {
            if ($this->UserProfile->save($this->request->data)) {
                $this->Flash->success('Profile created successfully.');
                return $this->redirect(array('controller' => 'users', 'action' => 'login'));
            } else {
                $this->Flash->error('Failed to create profile.');
            }
        }
        if (array_key_exists('UserImage', $this->request->data)) {
            $this->changeImage();
        }
    
        $this->set('user', $this->Auth->user());
    }

    public function changeImage() {
		$id = $this->Auth->user('id');

		$fileOK = $this->uploadFiles(array(
				'folder' => 'img/uploads', 
				'formdata' => array($this->request->data['UserImage']['image-upload'])
			));
		$image = NULL;

		//check if image passes checkers
		if(array_key_exists('filename', $fileOK)) {
			//$this->deletePrevImage('img/uploads');

			$image = $fileOK['filename'];
		}

		//check if image had errors
		if (array_key_exists('errors', $fileOK)) {
			$this->Session->setFlash(implode('<br>',$fileOK['errors']),'default', array(), 'account');
		}

		$this->UserProfile->validate = array();
        $this->UserProfile->read(array('img', 'img_dir'), array('UserProfile.user_id' => $id));
		$this->UserProfile->set(array('img' => $image, 'img_dir' => null));
		$res = $this->UserProfile->save();

		return $this->redirect(myTools::getUrl() . '/user/account');
	}
}

