/*
Embedded document PDF, Office and many others format
via Google Docs Viewer iFrame
Author -vito-
http://opencartmodding.com/extensions/docsembedder
*/
( function() {

	function getQueryVariable(query, variable) {
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
	}
	
	CKEDITOR.plugins.add ('docsembedder', {
		requires: [ 'dialog', 'fakeobjects' ],
		icons: 'docsembedder',
		hidpi: true,
		lang: [ 'en', 'ru' ],
		init: function (editor)	{
			lang = editor.lang.docsembedder;
			
			editor.docsembedder_type = editor.plugins.iframe ? 'gdiframe' : 'iframe';
			
			editor.addCommand ('docsembedder', new CKEDITOR.dialogCommand ('docsEmbedderDialog'));
			
			editor.ui.addButton ('docsembedder', {
				label: lang.menubutton,
				command: 'docsembedder',
				toolbar: 'insert,70'
			});
			
			editor.on ('doubleclick', function (evt) {
				var element = evt.data.element;
				if ( element.is( 'img' ) && element.data( 'cke-real-element-type' ) == editor.docsembedder_type ){
					evt.data.dialog = 'docsEmbedderDialog';
				}
			});
			
			if (editor.contextMenu) {
				editor.addMenuGroup ('gDocsGroup');
				editor.addMenuItem ('gDocsEmbedder', {
					label: lang.contextmenu,
					icon: this.path + 'icons/docsembedder.png',
					command: 'docsembedder',
					group: 'gDocsGroup'
				});
			
				editor.contextMenu.addListener( function( element, selection ) {
					if ( element && element.is( 'img' ) && element.data( 'cke-real-element-type' ) == editor.docsembedder_type )
						return { gDocsEmbedder: CKEDITOR.TRISTATE_OFF };
				} );
			}
			
			CKEDITOR.dialog.add ('docsEmbedderDialog', this.path + 'dialogs/docsembedder.js');
		},
		afterInit: function (editor) {

			var dataProcessor = editor.dataProcessor,
				dataFilter = dataProcessor && dataProcessor.dataFilter;

			if ( dataFilter ) {
				dataFilter.addRules( {
					elements: {
						iframe: function( element ) {
							var attributes = element.attributes;
							if( attributes.src.indexOf('http://docs.google.com/viewer?') > -1 ){
								//get document's url from the src
								var query = attributes.src.replace('http://docs.google.com/viewer?','');
								var decodedurl = decodeURIComponent(getQueryVariable(query,'url'));
								//get src file extension and add extClass
								var re = /(?:\.([^.]+))?$/;
								var ext = re.exec(decodedurl)[1];
								var extClass = ext ? ' gd_'+ ext.toLowerCase() : '';
								return editor.createFakeParserElement( element, 'cke_gdiframe' + extClass, editor.docsembedder_type, true );
							}
							return null;
						}
					}
				} ,
				9);
			}
		},
		onLoad: function (editor) {
			//insert common style for representing document's fakeimages 
			CKEDITOR.addCss( 'img.cke_gdiframe' +
				'{' +
					'background-image: url(' + CKEDITOR.getUrl( this.path + 'images/gd_placeholder.png' ) + ');' +
					'background-color: rgb(230, 230, 230);' +
					'background-position: center center;' +
					'background-repeat: no-repeat;' +
					'border: 1px solid #a9a9a9;' +
					'width: 128px;' +
					'height: 128px;' +
				'}'
			);
			//get file-icons for representing document's fakeimages
			$.ajax({
				url: CKEDITOR.getUrl( this.path + 'css/docsembedder.css'),
				data: '',
				dataType: 'text',
				success: function (data){
					CKEDITOR.addCss (data);
				}
			});
		}
	} );
} )();