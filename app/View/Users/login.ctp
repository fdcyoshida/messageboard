<div class="users form">
    <?php echo $this->Flash->render('auth'); ?>
    <?php echo $this->Form->create('User'); ?>
        <fieldset>
            <legend><?php echo __('Login'); ?></legend>
            <?php echo $this->Form->input('email', array('label' => 'Email'));
            echo $this->Form->input('password', array('label' => 'Password'));
        ?>
        </fieldset>
    <?php echo $this->Form->end(__('Login')); ?>
</div>
