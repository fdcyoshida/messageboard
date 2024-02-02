<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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
    $(document).ready(function(){
        $('.datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0"
        });
    });
</script>


<h1>Edit User Profile</h1>


<?php echo $this->Form->create('User', array( 'url' => array('controller' => 'userprofiles', 'action' => 'edit'), 'enctype' => 'multipart/form-data')); ?>
<fieldset>
    <?php echo $this->Form->input('UserProfile.image', array( 'type' => 'file', 'id' => 'imageInput', 'onchange' => 'previewImage(this)')); ?>
    <?php echo $this->Html->image('uploads/' . $userProfile['UserProfile']['img'], array( 'alt' => 'Profile Image', 'width' => 200, 'height' => 200, 'id' => 'existingImage')); ?>
    <?php echo $this->Form->input('User.name', array('label' => 'Name', 'value' => $userProfile['User']['name'])); ?>
    <?php echo $this->Form->input('UserProfile.gender', array('label' => 'Gender', 'options' => array('male' => 'Male', 'female' => 'Female', 'other' => 'Other'), 'default' => $userProfile['UserProfile']['gender'])); ?>
    <?php echo $this->Form->input('UserProfile.birthday', array('type' => 'text',  'class' => 'datepicker', 'value' => $userProfile['UserProfile']['birthday'])); ?>
    <?php echo $this->Form->input('UserProfile.hobby', array('label' => 'Hobby', 'type' => 'text', 'value' => $userProfile['UserProfile']['hobby'])); ?>
    <?php echo $this->Form->submit('Update'); ?>
    <?php echo $this->Form->end(); ?>
</fieldset>

