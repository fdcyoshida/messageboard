<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function previewImage(input) {
    var file = input.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#existingImage').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
    }
}
</script>

<h1>Edit User Profile</h1>

<?php echo $this->Form->create('User', array('url' => array('controller' => 'userprofiles', 'action' => 'update'), 'enctype' => 'multipart/form-data')); ?>

<?php echo $this->Form->input('UserProfile.image', array('type' => 'file', 'id' => 'imageInput', 'onchange' => 'previewImage(this);')); ?>

<?php echo $this->Html->image('uploads/' . $userProfile['UserProfile']['img'], array('alt' => 'Profile Image', 'width' => 200, 'height' => 200, 'id' => 'existingImage'));?>

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

<input type="submit" value="Update">

<?php echo $this->Form->end(); ?>
