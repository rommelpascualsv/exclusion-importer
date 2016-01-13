import $ from 'jquery';
import 'bootstrap';

$('.create-tables-btn').click(() =>{
    $.get("/import/createOldTables");
});

$('.start-btn').click(function() {
    const $btn = $(this);

    const altUrl = $btn.parents('tr').find('.url').html();

    let url = $btn.data('action');

    if (altUrl) {
        url += "?url=" + encodeURIComponent(altUrl);
    }

    $btn.html('Running..');

    $.get(url, data => {
        if (!data.success) {

            let modal = $('#error-modal');

            $btn.removeClass('btn-default')
                .addClass('btn-danger')
                .html('Error')
                .attr('disabled', true);

            modal.find('.modal-body').html(data.msg);
            modal.modal();

            return;
        }

        $btn.removeClass('btn-default')
            .addClass('btn-success')
            .html('Done!')
            .attr('disabled', true);

    }).fail(() => {
        $btn.removeClass('btn-default')
            .addClass('btn-danger')
            .html('Error')
            .attr('disabled', true);
    });
});
