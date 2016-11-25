/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	//config.language='en';
	config.height=400;
  config.entities = false;

  config.entities_greek = false;
  config.entities_latin = false;
  config.htmlEncodeOutput = false;
	config.extraPlugins = 'image2,btgrid,colordialog,pbckcode,codemirror,textselection,oembed,widget,lineutils,youtube,slideshow,widgetbootstrap,widgettemplatemenu,quicktable,tableresize,autocorrect,wordcount,lineheight,zoom,backgrounds,ckeditor-gwf-plugin,letterspacing,nbsp,simplebutton,wenzgmap,osem_googlemaps,videosnapshot,qrc,symbol,html5validation,extraformattributes';
  config.pbckcode = {
         cls : '',
         highlighter : 'PRETTIFY',
         modes :  [ ['HTML', 'html'], ['CSS', 'css'], ['PHP', 'php'], ['JS', 'javascript'] ],
         tab_size : '4'
     };
	
	config.codemirror_theme='paraiso-light';
	config.oembed_maxWidth = '560';
	config.oembed_maxHeight = '315';

	config.font_names = 'GoogleWebFonts;'+
			'Arial/Arial, Helvetica, sans-serif;' +
			'Comic Sans MS/Comic Sans MS, cursive;' +
			'Courier New/Courier New, Courier, monospace;' +
			'Georgia/Georgia, serif;' +
			'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;' +
			'Tahoma/Tahoma, Geneva, sans-serif;' +
			'Times New Roman/Times New Roman, Times, serif;' +
			'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;' +
			'Verdana/Verdana, Geneva, sans-serif';

	config.allowedContent = true; 
	config.wordcount_showCharCount =  true;
  
  	config.toolbar = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'pbckcode', 'Source', '-', 'autoFormat','CommentSelectedRange','UncommentSelectedRange','AutoComplete','-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt','AutoCorrect' ] },
		{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-' ] },
		{ name: 'insert', items: ['Image', 'Flash', 'btgrid', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar','Symbol', 'PageBreak', 'Iframe', '-', 'Youtube','oembed','videosnapshot', '-','wenzgmap','osem_googlemaps', '-','qrc','simplebutton','WidgetTemplateMenu','Slideshow' ] },
		'/',
		{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize','lineheight','letterspacing' ] },
		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
		{ name: 'tools', items: [ 'Zoom','Maximize', 'ShowBlocks' ] },
		{ name: 'others', items: [ '-', 'BidiLtr', 'BidiRtl','Language' ] },
	];

};