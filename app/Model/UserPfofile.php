<?php
App::uses('AppModel', 'Model');

class UserProfile extends AppModel {
    public $belongsTo = 'User';
    public $useTable = 'UserProfiles';

    public $actsAs = array(
        'Upload.Upload' => array(
            'img' => array(
                'fields' => array(
                    'dir' => 'img_dir'
                )
            )
        )
    );
}