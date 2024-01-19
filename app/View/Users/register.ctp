<?php
?>
<div>
    <h1>Regisration</h1>

    <form action="create" method="post">
        <label for="User_name">Name:</label>
        <input type="text" id="User_name" name="name">

        <label for="User_email">E-mail:</label>
        <input type="text" id="User_email" name="email">

        <label for="User_password">Password:</label>
        <input type="password" id="User_password" name="password">

        <label for="User_confirm_password">Confirm Password:</label>
        <input type="password" id="User_confirm_password" name="confirm_password">

        <input type="submit" value="Submit">
    </form>
</div>