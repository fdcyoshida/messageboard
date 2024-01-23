<?php echo $this->Form->create('UserProfile', array('type' => 'file', 'url' => array('controller' => 'user_profiles', 'action' => 'create'))); ?>

    <fieldset>
        <legend>Create Profile</legend>

        <label for="UserProfileImg">Profile Image:</label>
        <?php echo $this->Form->file('img'); ?>
        <?php echo $this->Form->create('UserImage',
			array(
				'type' => 'file',
				'url' =>
						array(
								'controller' => 'userprofile',
								'action' => 'create',
								'referrer' => $referrer,
								'language' => $localizeDir
							),
				'class' => 'thumb'
			)); ?>
        <img id="profile-photo" src="<?php echo $user->getImageUrl(); ?>">
        <?php echo $this->Form->input('image_url', array(
			'type' => 'hidden',
			'label' => false,
			'value' => $user->image
		)) ?>
        <button type="button">change image</button>
        <?php echo $this->Form->end(); ?>
                        
        <label for="UserProfileGender">Gender:</label>
        <select name="data[UserProfile][gender]" id="UserProfileGender">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="female">Other</option>
        </select>

        <label for="UserProfileBirthday">Birthday:</label>
        <?php echo $this->Form->input('birthday', array('type' => 'date')); ?>

        <label for="UserProfileHobby">Hobby:</label>
        <?php echo $this->Form->input('hobby', array('type' => 'text')); ?>
    </fieldset>
    <input type="submit" value="Submit">

<?php echo $this->Form->end(); ?>
