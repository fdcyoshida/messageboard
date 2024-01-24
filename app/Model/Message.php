<?php
App::uses('AppModel', 'Model');

class Message extends AppModel {
    public $belongsTo = array(
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'sender_id'
        ),
        'Receiver' => array(
            'className' => 'User',
            'foreignKey' => 'receiver_id'
        )
    );

    public $validate = array(
        'text' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'message' => 'Message text cannot be empty.'
        )
    );
}