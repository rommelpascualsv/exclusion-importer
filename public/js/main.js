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

    $btn.removeClass('btn-danger')
        .removeClass('btn-success')
        .addClass('btn-default')
        .attr('value', 'Running...');

    $.get(url, function(responseData) {
        
        if (responseData.success) {

            $btn.removeClass('btn-default')
                .addClass('btn-success')
                .attr('value', 'Done!')
                .attr('disabled', true);
            
            var readyForUpdate = $btn.parents('tr').children("td.readyForUpdate")[0];
            readyForUpdate.innerHTML = "No";
                
        } else {
        	
            $btn.removeClass('btn-default')
                .addClass('btn-danger')
                .attr('value', 'Error')
                .attr('disabled', true);
        }
        
        if (responseData.message) {
            var modal = $('#error-modal');
            modal.find('.modal-title').html(responseData.success ? 'Info' : 'Error');
            modal.find('.modal-body').html(responseData.message);
            modal.modal();
        }
        
        if (responseData.data) {
        	
        	var data = responseData.data,
        	    prefix = data.prefix,
                importResults = data.importResults,
        	    fileHash = importResults.fileHash,
                importTS = importResults.importTS ? importResults.importTS : '--',
        	    importStats = importResults.importStats,
                added = importStats.added ? importStats.added : 0,
                deleted = importStats.deleted ? importStats.deleted : 0,
                previousRecordCount = importStats.previousRecordCount ? importStats.previousRecordCount : 0,
                currentRecordCount = importStats.currentRecordCount ? importStats.currentRecordCount : 0;
            
            $('#' + prefix + '-last-import-ts').html(importTS);
            $('#' + prefix + '-current-record-count').html(currentRecordCount);
            $('#' + prefix + '-previous-record-count').html(previousRecordCount);
        	$('#' + prefix + '-added').html(added);
            $('#' + prefix + '-deleted').html(deleted);
        }


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
