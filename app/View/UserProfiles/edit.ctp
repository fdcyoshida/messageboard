<h1>Edit User Profile</h1>

<?php
echo $this->Form->create('UserProfile', array(
    'url' => array('controller' => 'userprofiles', 'action' => 'update'),
    'enctype' => 'multipart/form-data'
));
?>
<script>
    $(document).ready(function(){
        $("#imageInput").change(function(){
            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imagePreview').attr('src', e.target.result);
                    $('#imagePreview').show();
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    });
</script>

<?php echo $this->Form->input('image', array('type' => 'file', 'id' => 'imageInput')); ?>
<?php if (!empty($userProfile['UserProfile']['img'])) {
        echo $this->Html->image($userProfile['UserProfile']['img'], array('alt' => 'Profile Image', 'width' => 200, 'height' => 200));
    } else {
        echo '<img id="imagePreview" src="#" alt="Preview" style="max-width: 200px; display: none;">';
    }
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

<input type="submit" value="Update">

<?php echo $this->Form->end(); ?>
