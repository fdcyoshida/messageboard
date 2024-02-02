$(document).ready(function() {
    $('.show-more-btn').click(function() {
        var nextPageUrl = $('.pagination .next a').attr('href');

        if (nextPageUrl) {
            $.ajax({
                url: nextPageUrl,
                success: function(data) {
                    $('.messages-container').append(data);
                }
            });
        }
    });
});