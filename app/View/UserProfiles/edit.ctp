<?php
echo $this->Form->create('UserProfile', array(
    'url' => array('controller' => 'userprofiles', 'action' => 'update'),
    'enctype' => 'multipart/form-data'
));

?>


<label for="UserProfileName">Name:</label>
<input type="text" name="User[name]" value="<?php echo $userProfile['User']['name']; ?>">

<label for="UserProfileGender">Gender:</label>
<select name="UserProfile[gender]">
    <option value="male" <?php echo ($userProfile['UserProfile']['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
    <option value="female" <?php echo ($userProfile['UserProfile']['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
    <option value="other" <?php echo ($userProfile['UserProfile']['gender'] === 'other') ? 'selected' : ''; ?>>Other</option>
</select>

<label for="UserProfileBirthday">Birthday:</label>
<input type="date" name="UserProfile[birthday]" value="<?php echo $userProfile['UserProfile']['birthday']; ?>">

<label for="UserProfileHobby">Hobby:</label>
<input type="text" name="UserProfile[hobby]" value="<?php echo $userProfile['UserProfile']['hobby']; ?>">

<label for="UserProfileImage">Profile Image:</label>
<?php echo $this->Form->input('image', array('type' => 'file', 'id' => 'imageInput')); ?>

<input type="submit" value="Update">

<?php echo $this->Form->end(); ?>
