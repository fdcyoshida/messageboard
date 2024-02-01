<div>
    <h1>Registration</h1>
    <?php echo $this->Form->create('User', array('url' => array('controller' => 'Users', 'action' => 'register'))); ?>

        <?php echo $this->Form->input('name');?>
        <?php echo $this->Form->input('email'); ?>
        <?php echo $this->Form->input('password'); ?>


        <label for="User_confirm_password">Confirm Password:</label>
        <input type="password" id="User_confirm_password" name="confirm_password">


    <?php echo $this->Form->end(__('Submit')); ?>
</div>