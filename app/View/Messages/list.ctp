<script>
     function confirmDelete() {
        return confirm('Are you sure you want to destroy this conversation?');
    }
</script><h1>Message List</h1>
<div>
    <?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?>
</div>
<div>
    <?php echo $this->Html->link('New Message', ['controller' => 'Messages', 'action' => 'new'], ['class' => 'button']); ?>
</div>

<?php foreach ($latestMessages as $messageGroup): ?>
    <?php
    $sender = $messageGroup['Sender'];
    $userProfile = $messageGroup['UserProfile'];
    $latestMessage = $messageGroup['Message'];
    ?>

    <div class="message-group">
        <div class="sender-info">
            <?php
            echo $this->Html->image(
                $userProfile['img'],
                ['alt' => 'Profile Image', 'width' => 100, 'height' => 100]
            );
            ?>
            <p><?php echo h($sender['name']); ?></p>
        </div>

        <div class="message-content">
            <p><?php echo h($latestMessage['text']); ?></p>
            <p><?php echo h(date('Y/m/d H:i', strtotime($latestMessage['created']))); ?></p>
            <?php
            echo $this->Form->create('Message', [
                    'url' => ['controller' => 'messages', 'action' => 'detail'],
                    'class' => 'detail-conversation-form',
                    'type' => 'get',
                ]);

            echo $this->Form->hidden('first_user_id', ['value' => $latestMessage['sender_id']]);
            echo $this->Form->hidden('second_user_id', ['value' => $latestMessage['receiver_id']]);
            echo $this->Form->button('Details');

            echo $this->Form->end();
            ?>
            <?php
            echo $this->Form->create('Message', [
                'url' => ['controller' => 'messages', 'action' => 'destroyConversation'],
                'class' => 'destroy-conversation-form',
                'id' => 'destroy-conversation-form-' . $latestMessage['sender_id'],
                'onsubmit' => 'return confirmDelete();',
            ]);

            echo $this->Form->hidden('first_user_id', ['value' => $latestMessage['sender_id']]);
            echo $this->Form->hidden('second_user_id', ['value' => $latestMessage['receiver_id']]);
            echo $this->Form->button('Destroy');
            
            echo $this->Form->end();
            ?>
        </div>
    </div>
<?php endforeach; ?>