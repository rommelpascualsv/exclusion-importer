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
            
            start: function(file) {

                clearProgress();
                
                clearFileUploadMessage();
                
                if (!prefix) {
                	
                    setFileUploadMessage('No prefix in the request');
                    disableUploadButton(false);                   
                    return false;
                    
                } else {
                	
                    setUploadButtonToRunningState();
                    
                    setStartButtonToRunningState(prefix);                    
                }
                
            },
            
            progress: function(progress) {
            	 if (progress == 100) {
                    $('#fileupload-progress').html('Importing data...');
            	 } else {
            	 	$('#fileupload-progress').html('Upload Progress : ' + Math.round(progress) + '%');
            	 }
            },
            
            success: function(responseData) {
                
                var success = responseData ? responseData.success : false,
                    message = responseData ? responseData.message : 'No response received from the server',
                    data = responseData ? responseData.data || {} : {},
                    prefix = data.prefix || '',
                    url = data.url || '';
                
                clearProgress();
                
                setUploadButtonToInitialState();
                
                if (success) {
                	
                    setFileUploadMessage(message && message !== '' ?  message : 'Successfully uploaded and imported file!', 'info');
                    
                    setStartButtonToDoneState(prefix);
                    
                    // Update the url
                    $('#text_' + prefix).val(url);
                    
                } else {
                	
                    setFileUploadMessage(message, 'error');
                    
                    setStartButtonToErrorState(prefix);
                }
            },

            error: function(error) {
                
                clearProgress();            	
                
                setUploadButtonToInitialState();

            	setFileUploadMessage(error ? error.message : 'An error occurred while uploading the file', 'error');
            	
                setStartButtonToInitialState(prefix, startButtonDisabled);
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

/**
 * Sets the message to display in the file upload modal's status message container
 * 
 * @param {String} message the message to display
 * @param {String} category 'info' or 'error'
 */
function setFileUploadMessage(message, category) {
	$('#fileupload-message')
	   .text(message)
	   .toggleClass('text-info', category === 'info' || !category)
	   .toggleClass('text-danger', category === 'error');
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

function clearProgress() {
    $('#fileupload-progress').html('');
}

