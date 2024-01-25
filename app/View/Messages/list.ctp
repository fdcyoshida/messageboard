<!-- app/View/Messages/list.ctp -->

<h1>Latest Messages</h1>
<?php echo $this->Html->link('New Message', ['controller' => 'Messages', 'action' => 'new'], ['class' => 'button']); ?>

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
        </div>
    </div>
<?php endforeach; ?>
                