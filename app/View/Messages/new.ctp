<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<?php
    echo $this->Form->create('Message', array('url' => array('controller' => 'messages', 'action' => 'send')));
    echo $this->Form->input('User.name', array('id' => 'userSelect'));
    echo $this->Form->input('text', array('label' => 'message'));
    echo $this->Form->submit('send');
    echo $this->Form->end();
?>
<script>
$(document).ready(function() {
    $('#userSelect').select2({
        ajax: {
            url: '/messages/getUserNames',
            dataType: 'json',
            processResults: function(data) {
                return {
                    results: data
                };
            }
        }
    });
});
</script>