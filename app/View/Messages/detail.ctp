<h1>Message Details</h1>
<?php 
echo $this->Html->link(
    'Message list',
    array('controller' => 'messages', 'action' => 'list'),
    array('class' => 'button')
);
?>

<?php foreach ($messages as $message): ?>
    <div>
        <?php echo $this->Html->image($message['SenderProfile']['sender_img'], ['alt' => 'Profile Image', 'width' => 50, 'height' => 50]);?>
        <p><?php echo h($message['Sender']['sender_name']); ?></p>
        <p><?php echo h($message['Message']['text']); ?></p>
        <p>Sent at: <?php echo h(date('Y/m/d H:i', strtotime($message['Message']['created']))); ?></p>
    </div>
<?php endforeach; ?>