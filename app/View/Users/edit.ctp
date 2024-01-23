<?php
echo $this->Form->create('User', array(
    'url' => array('controller' => 'users', 'action' => 'update'),
));
?>
<h1>Edit User Profile</h1>


<label for="UserEmail">Email:</label>
<input type="email" name="User[email]" value="<?php echo h($user['email']); ?>">

<label for="UserPassword">New Password:</label>
<input type="password" name="User[new_password]">

<label for="UserConfirmPassword">Confirm New Password:</label>
<input type="password" name="User[confirm_password]">

<input type="submit" value="Update">

<?php echo $this->Form->end(); ?>