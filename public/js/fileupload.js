$(document).ready(function() {
	
    $('#fileupload-btn').click(function() {
    	
        var fileInput = $('#fileupload-file'),
            prefix = $('#fileupload-prefix').val(),
            // Need to remember the initial state of the 'Start' button in the main screen so we can restore it later
            startButtonDisabled = $('.start-btn[data-prefix=' + prefix + ']').is(':disabled');
        
        fileInput.simpleUpload('/import/upload', {

            allowedExts : ["csv", "pdf", "tsv", "txt", "xls", "xlsx", "xml", "zip", "html"],
            allowedTypes: ["text/csv", "application/pdf", "text/tab-separated-values", "text/plain", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/xml", "application/zip", "text/html"],
            maxFileSize : 100000000, //100MB
            
            data : {
                prefix : prefix
            },
            
            init : function(totalUploads) {

            	clearProgress();
                
                clearFileUploadMessage();
                
                if (!prefix) {
                    
                    setFileUploadMessage('No prefix in the request');
                    disableUploadButton(false);
                    disableUploadIcon(prefix, false);
                    return false;
                    
                } else {
                    
                	setProgress('Please wait. Uploading files... <i class="icon-spinner animate-spin"></i>');
                	
                	setUploadButtonToRunningState();
                    
                    setStartButtonToUploadingState(prefix);
                    
                    disableUploadIcon(prefix);
                }                
            },
            
            success: function(responseData) {
                
                var success = responseData ? responseData.success : false,
                    message = responseData ? responseData.message : 'No response received from the server',
                    data = responseData ? responseData.data || {} : {},
                    prefix = data.prefix || '',
                    url = data.url || '';
                
                // Note : 'this' is an object that is specific to each file and can 
                // be used to store and retrieve data related to that file (refer
                // to http://simpleupload.michaelcbrook.com/#docs for more details)
                
                if (success) {

                    this.importUrl = url;
                    
                } else {
                	
                    this.upload.cancel();
                    
                    this.upload.state = 'error';
                    
                    clearProgress();
                    
                	setFileUploadMessage(message, 'error');
                    
                    setStartButtonToErrorState(prefix);
                }
            },
            
            finish: function() {
            	
                clearProgress();
                
                setUploadButtonToInitialState();
                
                disableUploadIcon(prefix, false);
                
                var url = getImportUrlFrom(this.files);
                
                if (!url) {
                	return;
                }
                
                setUrl(prefix, url);
                
                importFile(prefix, url);
                
                hideFileUploadDialog();
            },            

            error: function(error) {
                
                this.upload.cancel();

            	clearProgress();            	
                
                setUploadButtonToInitialState();

            	setFileUploadMessage(error ? error.message : 'An error occurred while uploading the file', 'error');
            	
                setStartButtonToInitialState(prefix, startButtonDisabled);
                
                disableUploadIcon(prefix, false);
                
            }

        });

    });
    
    $('#fileupload-file').change(function() {
        resetFileUploadDialog();
    });    
});

function showFileUploadDialog(prefix, accr) {
    
    resetFileUploadDialog();
    
    $('#fileupload-prefix').val(prefix);
    
    $('#fileupload-file-label').html('Choose a file to upload for \'' + accr + '\' :');
    
    $('#fileupload-modal').modal(); 
}

function hideFileUploadDialog() {
	$('#fileupload-modal').modal('toggle');
}

/**
 * Sets the message to display in the file upload modal's status message container,
 * if the file upload dialog is visible. If the file upload dialog is not visible,
 * displays the message in the alert modal
 * 
 * @param {String} message the message to display
 * @param {String} category 'info' or 'error'
 */
function setFileUploadMessage(message, category) {
	
	
	if ($('#fileupload-modal').is(':visible')) {
		
        var iconCls = 'icon-info-circled-alt';
        
        if (category === 'error') {
            iconCls = ' icon-warning-empty';
        }
        
        $('#fileupload-message')
           .html('<table style="width:100%;padding:0">' +
                     '<tr>' +
                         '<td style="vertical-align:top;width:20px;"><i class="' + iconCls + '"></i></td>' +
                         '<td>' + message + '</td>' +
                     '</tr>' +
                 '</table>'
           )
           .toggleClass('text-info', category === 'info' || !category)
           .toggleClass('text-danger', category === 'error');
           
	} else {
		
		// Since the file upload dialog is not visible, show the message in the 
		// alert modal
		showMessage(category === 'error' ? 'Error' : 'Info', message);
	}

}

/**
 * Enables / disables the 'Upload' button in the file upload modal
 * @param {boolean} disable true or do not pass a value to disable,  false to enable
 */
function disableUploadButton(disable) {
	$('#fileupload-btn').prop('disabled', (disable === undefined || disable === true));
}

function setUploadButtonToRunningState() {
	disableUploadButton();
    $('#fileupload-btn').html('Running...'); 	
}

function setUploadButtonToInitialState() {
    disableUploadButton(false);
    $('#fileupload-btn').html('Upload');                 
}

function resetFileUploadDialog() {
	clearFileUploadMessage();
	clearProgress();
	setUploadButtonToInitialState();
}

/**
 * Clears any message currently set in the file upload modal's status message container
 */
function clearFileUploadMessage() {
    $('#fileupload-message').text('')
}


function setProgress(html) {
    $('#fileupload-progress').html(html); 	
}

function clearProgress() {
    $('#fileupload-progress').html('');
}

function getImportUrlFrom(files) {
	if (! files) {
		return null;
	}
	
	var importUrls;
	
    $.each(files, function(index, file) {
        
        if (file.upload.state === 'error') {
            return null;
        }
        
        if (!importUrls) {
            importUrls = [];
        }
        
        importUrls.push(file.importUrl);
    });
    
    return importUrls ? importUrls.join() : null;	
}

function setUrl(prefix, value) {
    $('#text_' + prefix).val(value);    
}

function setStartButtonToUploadingState(prefix) {
    $('.start-btn[data-prefix=' + prefix + ']')
        .removeClass('btn-danger')
        .removeClass('btn-success')
        .addClass('btn-default')
        .attr('value', 'Uploading...')
        .attr('disabled', true);
}

function setStartButtonToInitialState(prefix, disabled) {
    $('.start-btn[data-prefix=' + prefix + ']')
        .removeClass('btn-danger')
        .removeClass('btn-success')
        .addClass('btn-default')
        .attr('value', 'Start')
        .attr('disabled', disabled);
}
