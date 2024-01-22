<h2>Login</h2>
<form action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>" method="post">
    <label for="User_email">Email:</label>
    <input type="text" id="User_email" name="email">

    <label for="User_password">Password:</label>
    <input type="password" id="User_password" name="password">

    <input type="submit" value="Login">
</form>
