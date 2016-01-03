import $ from 'jquery';
require('bootstrap');

$('.create-tables-btn').click(() =>{
    $.get("/import/createOldTables");
});

$('.start-btn').click(function(e) {

    const url = $(this).parents('tr').find('.url').html();

    const a = new Importer(this);

    a.upload(url);

});

class Importer {
    constructor(btn) {
        this.btn = $(btn);
    }
    responseSuccess(data) {

        if (!data.success) {

            let modal = $('#error-modal');

            this.btn.removeClass('btn-default')
                .addClass('btn-danger')
                .html('Error')
                .attr('disabled', true);

            modal.find('.modal-body').html(data.msg);
            modal.modal();

            return;
        }

        this.btn.removeClass('btn-default')
            .addClass('btn-success')
            .html('Done!')
            .attr('disabled', true);
    };

    responseFail(response) {

        console.log(response);

        this.btn.removeClass('btn-default')
            .addClass('btn-danger')
            .html('Error')
            .attr('disabled', true);
    };

    upload(appendedUrl) {

        let url = this.btn.data('action');

        if (appendedUrl) {
            url += "?url=" + encodeURIComponent(url);
        }

        this.btn.html('Running..');

        $.get(url, data => {
            this.responseSuccess(data);
        }).fail(response => {
            this.responseFail(response);
        });

    };
}

