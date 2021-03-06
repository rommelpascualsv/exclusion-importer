$(document).ready(function() {
	
    $('.create-tables-btn').click(function() {
        $.post("/exclusion-lists/create-old-tables");
    });
    
    $('.start-btn').click(function() {
        var $btn = $(this),
            url = $btn.parents('tr').children("td").children("input").val(),
            prefix = $btn.attr('data-prefix');
     
        importFile(prefix, url);
    });
    
    $('.icon-upload-cloud-outline').click(function() {
    	
    	if ($(this).hasClass('disabled')) {
    		return;
    	}
    	
        showFileUploadDialog($(this).attr('data-prefix'), $(this).attr('data-accr'));
    });
    
    $('.url').change(toggleButton);
    
    $('.url').keyup(toggleButton);
});

function importFile(prefix, exclusionListUrl) {

    setStartButtonToRunningState(prefix);
    
    disableUploadIcon(prefix);
    
    var url = '/exclusion-lists/import/' + prefix,
        data = {url : exclusionListUrl},
        callback = onFileImportResponse.bind(this, prefix);

    $.post(url, data, callback).fail(onFileImportFail);
}


function onFileImportResponse(prefix, responseData) {
	
	disableUploadIcon(prefix, false);
	
    if (responseData.success) {
        
        setStartButtonToDoneState(prefix);
        
        setUpdateRequired(prefix, 'No');
            
    } else {
        
        setStartButtonToErrorState(prefix);
    }
    
    if (responseData.message) {
        showMessage(responseData.success ? 'Info' : 'Error', responseData.message);
    }
    
    if (! responseData.data) {
        return; 	
    }
        
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

function onFileImportFail(xhr, statusTxt) {
    showMessage('Failed to import file : ' + (statusTxt || '') + (xhr && xhr.responseText ? (' - ' + xhr.responseText) : ''));
}

function toggleButton() {
	var txtFld = $(this),
	    btn = txtFld.parents('tr').children("td").children("input.start-btn");
    
    if (txtFld.val() != "") {
    	btn.attr('disabled', false);
    } else {
    	btn.attr('disabled', true);
    }
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

function disableUploadIcon(prefix, disable) {
    $('.icon-upload-cloud-outline[data-prefix=' + prefix + ']')
        .toggleClass('disabled', disable == undefined || disable == true);
}

function setUpdateRequired(prefix, value) {
	$('td.readyForUpdate[data-prefix=' + prefix+ ']').html(value);	
}
