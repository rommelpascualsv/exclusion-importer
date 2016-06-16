function showMessage(title, message) {
    var modal = $('#error-modal');
    modal.find('.modal-title').html(title);
    modal.find('.modal-body').html(message);
    modal.modal();
}
