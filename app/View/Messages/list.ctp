<!-- app/View/Messages/list.ctp -->

<h2>Latest Messages</h2>

<?php foreach ($latestMessages as $message): ?>
    <div class="message">
        <p>
            <strong><?= h($message['SenderUserProfile']['User']['name']) ?></strong>
            <br>
            <?= h($message['Message']['text']) ?>
        </p>
        <p class="sent-at">
            Sent at: <?= h($message['max_created_at']) ?>
        </p>
    </div>
<?php endforeach; ?>
