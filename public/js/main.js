$('.create-tables-btn').click(function() {
    $.get("/import/createOldTables");
});

$('.start-btn').click(function() {
    var $btn = $(this);

    var altUrl = $btn.parents('tr').children("td").children("input").val();

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
        
        var readyForUpdate = $btn.parents('tr').children("td.readyForUpdate")[0];
        readyForUpdate.innerHTML = "No";

    }).fail(function() {
        $btn.removeClass('btn-default')
            .addClass('btn-danger')
            .attr('value', 'Error')
            .attr('disabled', true);
    });
});

function toggleButton() {
	var $txtFld = $(this);

    var btn = $txtFld.parents('tr').children("td").children("input.start-btn");
    
    if ($txtFld.val() != "") {
    	btn.attr('disabled', false);
    } else {
    	btn.attr('disabled', true);
    }
}
$('.url').change(toggleButton);
$('.url').keyup(toggleButton);
