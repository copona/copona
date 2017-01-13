var lastLoadedFile = ''; // to indicate the last file we loaded

$(document).ready(function() {

	// load only non-translated pages when the user checks the "non-translated" button
	$('#notTranslated, #pageSearch').change(function(){
		loadTexts();
	});

	var typingTimer; // to keep track of how long since the user stopped typing in the search fields
	var doneTypingInterval = 500;  // the amount of time to wait before automatically submitting the search

	//on changing the search input, start tracking when the user stops typing
	$('#keySearch, #textSearch').on('input', function(){
	    clearTimeout(typingTimer);
	    // when the user stops typing long enough, perform the search, loading the texts as search results
	    typingTimer = setTimeout(loadTexts, doneTypingInterval);
	});

	// change the interface to frontend or backend when the user change the interface radio buttons
	$('input[name=interface]').change(function(){
		window.location = crtm.url+'reload&interface='+$('input[name=interface]:checked').val();
	});

	// show errors in a popup modal with the given title and data
	// if the "html" parameter is set, the data will be shown as html, otherwise as text
	function errorModal(title, data, html) {
		$('#errorModal .modal-title').text(title);
		if ( html === undefined ) {
			$('#errorModal .modal-body').text(data);	
		}
		else {
			$('#errorModal .modal-body').html(data);
		}
		$('#errorModal').modal('show');
	}

	// check ajax response data for an HTML page
	// if the data begins with "<!DOCTYPE", Opencart's probably showing an error
	function opencartError(data) {
		if (data.indexOf("<!DOCTYPE") > -1 ) {
			errIndex = data.indexOf("<div class=\"alert alert-danger\">")
			if ( errIndex > -1 ) {
				var error = data.substring(errIndex);
				error = error.substring(0, error.indexOf('</div>')+6);
				errorModal(crtm.error_error, error, 'html');
				return true;
			}
		}
		return false;
	}

	// Load texts into the translation table (overwriting table contents unless "append" is set)
	function loadTexts(append) {
		var params = {
			length : 20, // minimum number of new texts to load
			dirKey : $('input[name=interface]:checked').val(), // admin or catalog
			notTranslated : $('#notTranslated').is(':checked'), // show only non-translated texts
			singleFile : $('#pageSearch').val(), // load a single file or all files
			keyFilter : $('#keySearch').val(), // filter by key
			textFilter : $('#textSearch').val() // filter by text
		};
		if ( lastLoadedFile != '' && append !== undefined) {
			params.startAfter = lastLoadedFile; // start after the last loaded file
		}

		$('#translateFormsContainer').fadeTo(500, 0.25);
		
		$.get(crtm.url+'load', params)
		.done(function(data){
			try { // check that the returned data is a valid JSON object
				data = JSON.parse(data);

				// if the results DON'T have an 'html' property that begins with "<!-- Loaded", something's not right
				if ( !data.hasOwnProperty('html') || data.html.indexOf("<!-- Loaded") === -1 ) {
					errorModal(crtm.error_unexpected, JSON.stringify(data, null, 2));
				}
				else {
					if ( data.hasOwnProperty('lastFile') ) {
						lastLoadedFile = data.lastFile;
					}
					if ( params.singleFile !== "" || append === undefined ) { // if loading a single file, remove all other strings
						$('#transTable tbody').html(data.html);
					}
					else { // otherwise just add the new strings below the old ones
						$('#transTable tbody').append(data.html);
					}
					$('#translateFormsContainer').fadeTo(500, 1);
				}
			}
			catch (e){
				// check for an Opencart error, or just display the response otherwise
				if ( !opencartError(data) ) {
					errorModal(crtm.error_error, data, 'html');
				}
			}
		})
		.fail(function(data) {
			errorModal(crtm.error_error, data.responseText, 'html');
		})
		.always(function(data) {
			// hide the "loading" message once results are returned.
			$('#loadingTextsWait').hide();
			// if no results, show the "noTextsFound" message
			var numTexts = $('.textRow').length;
			if ( numTexts == 0 ) { 
				$('#noTextsFound').show();
				$('#loadMoreBtn').hide();
			}
			else { 
				$('#noTextsFound').hide();
				$('#scrollToTopBtn').show();
				// if no more results, hide the "Load more" button
				// no more results if
				// 1. loading a single file, 
				// 2. less than the minimum number of parameters were returned
				// 3. or the last loaded file is an empty string
				if ( params.singleFile !== '' || numTexts < params['length'] || lastLoadedFile == '' ) {
					$('#loadMoreBtn').hide();	
				}
				else { // otherwise show the "Load more" button
					$('#loadMoreBtn').show();
				}
			}
		});
	}

	loadTexts(); // load the texts automatically once the page is first loaded

	// on clicking a text, show an editable textarea
	$('#transTable').on('click', '.translationCol', function(){
		$transDiv = $(this).find('.transDiv');
		if ( $transDiv.is(':visible') ) {
			$('.transTextArea').remove(); // remove any previous text areas before adding the new one
			$('.transDiv').show(); // also ensure any previously-hidden text is now displayed
			// get the text to be translated (or an empty string if not translated)
			var text = $transDiv.find('.notTranslatedSpan').length ? '' : $transDiv.html();
			// create and append the new text area
			$textarea = $('<textarea name="translation" class="transTextArea">'+text+'</textarea>');
	        $(this).append($textarea);
	        
	        $transDiv.hide(); // hide the original text
	        showCtrls($textarea); // add save and cancel controls to the textarea
	        showLayer(); // cover the rest of the page in a semi-transparent background
			$textarea.focus(); // place the mouse cursor in this textarea
			$textarea.get(0).select(); // select all the text by default
        }
	});

	//hotkeys handler
	$('#transTable').on('keyup', '.transTextArea', function(e) {
		// save with "Cntl" + "Enter" (In some browsers, "Enter" = 10. In others, "Enter" = 13)
		if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey) {
			$('.saveTrans').click();
		}
		
		// cancel with the "Escape" key
		if( e.which === 27 ){
			$('.cancelTrans').click();
		}
	});

	// Freeze the table header so that it's always seen when scrolling
	$('#transTable').stickyTableHeaders();

	$('#loadMoreBtn').click(function(){
		loadTexts('append');
	});

	$('#scrollToTopBtn').click(function(){
		$('html, body').animate({ scrollTop: 0 }, 'fast');
	});

	function showCtrls($textarea) {
        //remove old controls if exists
        $('#ctrls-holder').remove();
    
        //create new controls
        var $ctrls = $('<div />').attr({
            'id' : 'ctrls-holder'
        });
        
        //get position of textarea
        var oPos = $textarea.position();

        //control buttons  
        var $ctrlOk = $('<a href="#" class="saveTrans" title="'+crtm.text_save_translation+'"><i class="fa fa-check">&nbsp;</i></a>');
        var $ctrlCancel = $('<a href="#" class="cancelTrans" title="'+crtm.text_cancel+'"><i class="fa fa-times">&nbsp;</i></a>');
        
        $ctrls.append($ctrlOk).append($ctrlCancel);
        
        $ctrls.css({
            'top' : oPos.top -15,
            'left' : oPos.left + $textarea.outerWidth()+1
        });
        
        //append new controls to the DOM
        $('body:first').append($ctrls);
    }

	function showLayer() {
        $('#layer').remove();
        var $layer = $('<div />').attr({
            'id' : 'layer'
        }).height($(document).height());
        
        $('body:first').append($layer);
        
        //cancel editing on click
        $layer.fadeTo(500, 0.65).click(function() {
            $('.cancelTrans').click();
        });
    }
    
    //hide semi-transparent BG
    function hideLayer() {
        $('#layer').fadeOut(500, function() {
            $('#layer').remove();
        });
    }

    //click on control buttons - Save or Cancel
    $('body').on('click', '#ctrls-holder a', function(e) {
        e.preventDefault();
        
        //cancel editing
        if ($(this).hasClass('cancelTrans')) {
        	// show the original text and remove all translation text areas
        	$('.transTextArea:first').closest('td').find('.transDiv').show();
            $('.transTextArea').remove();
            $('#ctrls-holder').remove();
            hideLayer();
        }
        
        //save translation
        if ($(this).hasClass('saveTrans')) {
        	// disable this link to prevent multiple clicks until the saving is done
        	$(this).addClass('link-disabled').blur().html('<i class="fa fa-spinner fa-spin">&nbsp;</i>');
        	saveTranslation($('.transTextArea:first'));
        }
    });

    function saveTranslation($textarea) {
    	var params = {
    		page : $textarea.closest('tr').attr('data-page'),
    		key : $textarea.closest('tr').find('.keyCol').text(),
    		lang : $textarea.closest('td').attr('data-lang'),
    		translation : $textarea.val(),
    		dirKey : $('input[name=interface]:checked').val(), // admin or catalog
    	}

    	$.post(crtm.url+'save', params)
		.done(function(data){

			try { // check that the returned data is a valid JSON object
				data = JSON.parse(data);
				// if the results DON'T have a 'success' property, something's not right
				if ( !data.hasOwnProperty('success') ) {
					errorModal(crtm.error_unexpected, JSON.stringify(data, null, 2));
				}
				else {
					if (data.success == '') { // show text not translated if the translation is deleted
						$textarea.siblings('.transDiv').html($('#notTranslatedTemplate').html());
					}
					else {
						$textarea.siblings('.transDiv').text(data.success);
					}
					$('.cancelTrans').click();
				}
			}
			catch (e){
				// check for an Opencart error, or just display the response otherwise
				if ( !opencartError(data) ) {
					errorModal(crtm.error_error, data, 'html');
				}
			}
		})
		.fail(function(data) {
			errorModal(crtm.error_error, data.responseText, 'html');
		})
    }

    // window resizing events
    $(window).resize(function() {
    	// regenerate the textarea controls at the correct position
    	if ( $('.transTextArea').length ) {
    		showCtrls($('.transTextArea:first'));
    	}
    });

    // capture textarea resizing (based on mouse movement) and correct the position of the controls
    // this may not be perfect, but better than nothing
    // Thanks for the help, MoonLite! http://stackoverflow.com/a/16848663/1193304
    $('body').on('mouseup mousemove', '.transTextArea', function(){
        if(this.oldwidth  === null){this.oldwidth  = this.style.width;}
        if(this.oldheight === null){this.oldheight = this.style.height;}
        if(this.style.width != this.oldwidth || this.style.height != this.oldheight){
            this.oldwidth  = this.style.width;
            this.oldheight = this.style.height;
            showCtrls($(this));
        }
    });
});