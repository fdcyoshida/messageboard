<?php
    echo $this->Form->create('Message', array('url' => array('controller' => 'messages', 'action' => 'send')));
    echo $this->Form->input('receiver_id', array('options' => $users, 'label' => 'Receiver'));
    echo $this->Form->input('text', array('label' => 'message'));
    echo $this->Form->submit('send');
    echo $this->Form->end();
?>