<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<?php
    echo $this->Form->create('Message', array('url' => array('controller' => 'messages', 'action' => 'send')));
    echo $this->Form->input('receiver_id', array('id' => 'userSelect', 'style' => 'width: 300px;'));
    echo $this->Form->input('text', array('label' => 'message'));
    echo $this->Form->submit('send');
    echo $this->Form->end();
?>
<script>
$(document).ready(function() {
    $('#userSelect').select2({
        ajax: {
            url: '<?php echo Router::url(array('controller' => 'messages', 'action' => 'getUsers')); ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                };
            },
            processResults: function(data) {
                var users = data.users;
                var results = users.map(function(user) {
                    return {
                        id: user.id,
                        text: user.name
                    };
                });
                return {
                    results: results
                };
            },
            cache: true
        },
        minimumInputLength: 1
    });
});
</script>