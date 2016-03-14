$('.create-tables-btn').click(function() {
    $.get("/import/createOldTables");
});

$('.start-btn').click(function() {
    var $btn = $(this);

    var altUrl = $btn.parents('tr').children("td").children("input").val();
    
//    var altUrl = $btn.parents('tr').find('.url').html();

    var url = $btn.data('action');

    if (altUrl) {
        url += "?url=" + encodeURIComponent(altUrl);
    }

    $btn.attr('value', 'Running..');

    $.get(url, function(data) {
        if (!data.success) {

            var modal = $('#error-modal');

            $btn.removeClass('btn-default')
                .addClass('btn-danger')
                .attr('value', 'Error')
                .attr('disabled', true);

            modal.find('.modal-body').html(data.msg);
            modal.modal();

            return;
        }

        $btn.removeClass('btn-default')
            .addClass('btn-success')
            .attr('value', 'Done!')
            .attr('disabled', true);

    }).fail(function() {
        $btn.removeClass('btn-default')
            .addClass('btn-danger')
            .attr('value', 'Error')
            .attr('disabled', true);
    });
});
