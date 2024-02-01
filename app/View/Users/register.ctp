<div>
    <h1>Registration</h1>
    <?php echo $this->Form->create('User', array('url' => array('controller' => 'Users', 'action' => 'register'))); ?>
        <?php echo $this->Form->input('name');?>
        <?php echo $this->Form->input('email'); ?>
        <?php echo $this->Form->input('password'); ?>
        <?php echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => 'Confirm Password')); ?>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>