<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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

<?php
echo $this->Form->create('UserProfile', array(
    'type' => 'file',
    'url' => array('controller' => 'user_profiles', 'action' => 'new'),
    'enctype' => 'multipart/form-data'
));
?>
<fieldset>
    <h1>Create Profile</h1>
    
    <img id="imagePreview" src="#" alt="Preview" style="max-width: 200px; display: none;">
    <?php echo $this->Form->input('image', array('type' => 'file', 'id' => 'imageInput')); ?>
    <?php echo $this->Form->input('User.name', array('type' => 'text', 'value' => $userName)); ?>
    <label for="UserProfileGender">Gender:</label>
    <?php echo $this->Form->input('gender', array('type' => 'select', 'options' => array(
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other'
    ))); ?>
    <?php echo $this->Form->input('birthday', array('type' => 'date')); ?>
    <?php echo $this->Form->input('hobby', array('type' => 'text')); ?>
</fieldset>

<input type="submit" value="Submit">
<?php echo $this->Form->end(); ?>
