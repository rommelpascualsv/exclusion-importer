import $ from 'jquery';
require('bootstrap');

function createtables(){
    var createTablesBtn = $('.start-btn');
}

function Import(btn) {

    this.btn = $(btn);

}

Import.prototype._responseSuccess = function(data) {

    var me = this;

    if ( ! data.success) {

        var modal = $('#error-modal');

        var modalBody = modal.find('.modal-body');

        me.btn.removeClass('btn-default')
            .addClass('btn-danger')
            .html('Error')
            .attr('disabled', true);

        modal.find('.modal-body').html(data.msg);
        modal.modal();

        return;
    }

    me.btn.removeClass('btn-default')
        .addClass('btn-success')
        .html('Done!')
        .attr('disabled', true);
};

Import.prototype._responseFail = function(response) {

    this.btn.removeClass('btn-default')
        .addClass('btn-danger')
        .html('Error')
        .attr('disabled', true);
};

Import.prototype.upload = function (url) {

    var appendedUrl = '';

    if (url) {
        appendedUrl = "?url=" + encodeURIComponent(url);
    }

    var me = this;

    me.btn.html('Running..');

    $.get(me.btn.data('action') + appendedUrl, function (data) {
            me._responseSuccess.call(me, data);
        })
        .fail(function(response) {
            me._responseFail.call(me, response);
        });

};


$(function() {

    var createTablesBtn = $('.create-tables-btn');

    createTablesBtn.click(function(e){
        $.get("/import/createOldTables");
    });

    var startButtons = $('.start-btn');

    startButtons.click(function(e) {

        var url = $(this).parents('tr').find('.url').html();

        var a = new Import(this);

        a.upload(url);

    });
});
