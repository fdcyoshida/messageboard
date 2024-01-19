<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $helpers = array('Html', 'Form');

    public function register() {
    }

    public function create() {
        if ($this->request->is('post')) {
            $userData = $this->request->data;

            // バリデーションなどの処理が必要な場合はここで行う
            CakeLog::write('debug', print_r($postData, true));
            $this->Session->setFlash('User registration successful.');

            $this->redirect($this->request->referer());


            //if ($this->User->save($userData)) {
                //$this->Session->setFlash('User registration successful.');
                //$this->redirect($this->request->referer());
            //} else {
                //$this->Session->setFlash('User registration failed.');
            //}

        }
    }

}
