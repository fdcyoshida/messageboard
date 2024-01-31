<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.reply-form').submit(function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var newMessage = response.replyMessage;                    
                    var messageHtml = '<div>' +
                        '<img src="<?php echo $this->webroot; ?>/img/uploads/' + newMessage['sender_img'] + '" alt="Profile Image" width="50" height="50">' +
                        '<p>' + newMessage['sender_name'] + '</p>' +
                        '<p>' + newMessage['text'] + '</p>' +
                        '<p>Sent at: ' + newMessage['created'] + '</p>' +
                        '</div>';
                    $('.messages-container').prepend(messageHtml);
                    
                    var container = $('.messages-container');
                    container.scrollTop(container[0].scrollHeight);
                }
            },
            error: function(error) {
            }
        });
    });
});
</script>

<h1>Message Details</h1>
<?php echo $this->Html->link('Message list', array('controller' => 'messages', 'action' => 'list'), array('class' => 'button'));?>
<div>
    <?php
        echo $this->Form->create('Message', [
            'url' => ['controller' => 'messages', 'action' => 'reply'],
            'class' => 'reply-form'
        ]);

        echo $this->Form->hidden('first_user_id', ['value' => $messages[0]['Message']['receiver_id']]);
        echo $this->Form->hidden('second_user_id', ['value' => $messages[0]['Message']['sender_id']]);

        echo $this->Form->textarea('text', ['label' => 'Your Reply']);
        echo $this->Form->button('Send Reply', ['class' => 'submit-reply-btn']);

        echo $this->Form->end();
    ?>
</div>
<div class="messages-container">
    <?php foreach ($messages as $message): ?>
        <div>
            <?php echo $this->Html->image('uploads/'.$message['SenderProfile']['sender_img'], ['alt' => 'Profile Image', 'width' => 50, 'height' => 50]);?>
            <p><?php echo h($message['Sender']['sender_name']); ?></p>
            <p><?php echo h($message['Message']['text']); ?></p>
            <p>Sent at: <?php echo h(date('Y/m/d H:i', strtotime($message['Message']['created']))); ?></p>

            <?php if ($message['Sender']['id'] == $loggedInUserId): ?>
                <?php echo $this->Html->link('Delete', ['controller' => 'messages', 'action' => 'destroyMessage', $message['Message']['id']], ['confirm' => 'Are you sure?']); ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>


<?php echo $this->Paginator->prev('« Previous'); ?>
<?php echo $this->Paginator->numbers(); ?>
<?php echo $this->Paginator->next('Next »'); ?>