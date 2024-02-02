<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');


class User extends AppModel {
    public $hasOne = array(
        'UserProfile' => array(
            'className' => 'UserProfile',
            'foreignKey' => 'user_id',
        )
    );
    
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Name cannot be empty'
            ),
            'length' => array(
                'rule' => ['lengthBetween', 5, 20],
                'message' => 'Name must be between 5 and 20 characters'
            )
        ),
        'email' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter an email address'
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter a valid email address format'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Email address already in use'
            )
        ),
        'password' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter a password'
            ),
            'minLength' => array(
                'rule' => array('minLength', 8),
                'message' => 'Password must be at least 8 characters long'
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

    public function beforeValidate($options = array()) {
        parent::beforeValidate($options);

        if (!empty($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = str_replace(' ', '', $this->data[$this->alias]['password']);
        }

        return true;
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
