<?php
?>
<h1>User Profile</h1>

<table>
    <tr>
        <td>
            <?php
                if (!empty($userProfile['UserProfile']['img'])) {
                    echo $this->Html->image(
                        $userProfile['UserProfile']['img'],
                        array('alt' => 'Profile Image', 'width' => 200, 'height' => 200)
                    );
                } else {
                    echo 'No image available';
                }
            ?>
        </td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?php echo h($userProfile['User']['name']); ?></td>
    </tr>
    <tr>
        <th>Gender</th>
        <td><?php echo h($userProfile['UserProfile']['gender']); ?></td>
    </tr>
    <tr>
        <th>Birthday</th>
        <td><?php echo h($userProfile['UserProfile']['birthday']); ?></td>
    </tr>
    <tr>
        <th>Hobby</th>
        <td><?php echo h($userProfile['UserProfile']['hobby']); ?></td>
    </tr>
</table>

<?php
echo $this->Html->link(
    'Edit Profile',
    array('controller' => 'userprofiles', 'action' => 'edit'),
    array('class' => 'button')
);
?>
