<?php
App::uses('AppModel', 'Model');

class UserProfile extends AppModel {
    public $useTable = 'UserProfiles';
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'dependent' => true,
        )
    );
    public $validate = array(
        'user_id' => array(
            'rule' => 'notBlank',
            'message' => 'User ID must not be empty'
        ),
        'img' => array(
            'extension' => array(
                'rule' => array('extension', array('jpg', 'jpeg', 'gif', 'png')),
                'message' => 'Please upload a valid image file (jpg, jpeg, gif, png)'
            ),
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Image must not be empty'
            )
        )
    );
}
