<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');


class User extends AppModel {
    public $validate = array(
        'email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter a valid email address format'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Email address already in use'
            )
        ),
        'confirm_password' => array(
            'compare' => array(
               'rule' => array('comparePasswords'),
                'message' => 'Passwords do not match',
                'on' => 'create'
            )
        )
    );

    public function comparePasswords($data) {
        return ($this->data[$this->alias]['password'] === $data['confirm_password']);
    }

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }
}
