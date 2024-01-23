<?php
App::uses('AppModel', 'Model');

class UserProfile extends AppModel {
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        )
    );
    public $useTable = 'UserProfiles';
}
