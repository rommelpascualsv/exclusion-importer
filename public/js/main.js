$(document).ready(function(){
	
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
    
        setStartButtonToRunningState($btn.attr('data-prefix'));
    
        $.get(url, function(responseData) {
            
            if (responseData.success) {
                
            	setStartButtonToDoneState($btn.attr('data-prefix'));

            	var readyForUpdate = $btn.parents('tr').children("td.readyForUpdate")[0];
            	
                readyForUpdate.innerHTML = "No";
                    
            } else {
                
            	setStartButtonToErrorState($btn.attr('data-prefix'));
            }
            
            if (responseData.message) {
                showMessage(responseData.success ? 'Info' : 'Error', responseData.message);
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
        })
        .fail(function(xhr, statusTxt) {
            showMessage('Failed to import file : ' + (statusTxt || '') + (xhr && xhr.responseText ? (' - ' + xhr.responseText) : ''));
        });
    });
    
    $('.icon-upload').click(function() {
        showFileUploadDialog($(this).attr('data-prefix'), $(this).attr('data-accr'))
    });
    
    $('.url').change(toggleButton);
    
    $('.url').keyup(toggleButton);
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

function setStartButtonToInitialState(prefix, disabled) {
    
    $('.start-btn[data-prefix=' + prefix + ']')
        .removeClass('btn-danger')
        .removeClass('btn-success')
        .addClass('btn-default')
        .attr('value', 'Start')
        .attr('disabled', disabled);
}


function setStartButtonToRunningState(prefix) {
	
    $('.start-btn[data-prefix=' + prefix + ']')
        .removeClass('btn-danger')
        .removeClass('btn-success')
        .addClass('btn-default')
        .attr('value', 'Running...')
        .attr('disabled', true);
}

function setStartButtonToDoneState(prefix) {
    $('.start-btn[data-prefix=' + prefix + ']')
        .removeClass('btn-default')
        .addClass('btn-success')
        .attr('value', 'Done!')
        .attr('disabled', true);
}

function setStartButtonToErrorState(prefix) {
    $('.start-btn[data-prefix=' + prefix + ']').removeClass('btn-default')
        .addClass('btn-danger')
        .attr('value', 'Error')
        .attr('disabled', true);
}