/*
Embedded document PDF, Office and many others format
via Google Docs Viewer iFrame
Author -vito-
http://opencartmodding.com/extensions/docsembedder
*/
( function () {

	function getQueryVariable(query, variable) {
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
	}

	CKEDITOR.dialog.add( 'docsEmbedderDialog', function( editor ) {
		var commonLang = editor.lang.common,
			docsEmbedderLang = editor.lang.docsembedder;
		return {
			title: docsEmbedderLang.title,
		    minWidth: 400,
			minHeight: 200,
			
			contents: [
				{
					id: 'tab-general',
					label: commonLang.generalTab,
					elements: [
						{
							type: 'hbox',
							widths: [ '90%', '10%; vertical-align: bottom' ],
							children: [
								{
									type: 'text',
									id: 'src',
									label: commonLang.url,
									labelLayout: 'vertical',
									validate: CKEDITOR.dialog.validate.notEmpty(docsEmbedderLang.emptyURL),
									setup: function (element) {
										var query = element.getAttribute("src").replace('http://docs.google.com/viewer?','');
										var decodedurl = decodeURIComponent(getQueryVariable(query,'url'));
										this.setValue (decodedurl);
									},
									commit: function (element) {
										element.setAttribute ("src", 'http://docs.google.com/viewer?url=' + encodeURIComponent( this.getValue() ) + '&embedded=true');
									}
								},
								{
									type: 'button',
									id: 'browse',
									label: commonLang.browseServer,
									filebrowser: 'tab-general:src'
								}
							]
						},
						{
							type: 'hbox',
							widths: ['50%', '50%'],
							children: [
								{
									type: 'text',
									id: 'width',
									label: commonLang.width,
									labelLayout: 'vertical',
									style: 'width:100%',
									validate: CKEDITOR.dialog.validate.htmlLength( commonLang.invalidHtmlLength.replace( '%1', commonLang.width ) ),
									setup: function (element) {
										this.setValue (element.getAttribute("width"));
									},
									commit: function (element) {
										var width = this.getValue();
										element.setAttribute ("width", width ? width : '600');
									}
								},
								{
									type: 'text',
									id: 'height',
									label: commonLang.height,
									labelLayout: 'vertical',
									style: 'width:100%',
									validate: CKEDITOR.dialog.validate.htmlLength( commonLang.invalidHtmlLength.replace( '%1', commonLang.height ) ),
									setup: function (element) {
										this.setValue (element.getAttribute("height"));
									},
									commit: function (element) {
										var height = this.getValue();
										element.setAttribute ("height",  height ? height : '780');
									}
								}
							]
						}
					]
				}
			],
			onShow: function() {
			
				this.fakeImage = this.gdiframe = null;
				
				var selection = editor.getSelection();
				var element = selection.getStartElement();
				if (element && element.data( 'cke-real-element-type' ) && element.data( 'cke-real-element-type' ) == editor.docsembedder_type){
					this.fakeImage = element;
					element = editor.restoreRealElement( element );
					this.gdiframe = element;
				}
				if (!element || element.getName() != 'iframe' ) {
					element = editor.document.createElement ('iframe');
					this.insertMode = true;
				} else {
					this.insertMode = false;
				}
					
				this.element = element;
				
				if (!this.insertMode) {
					this.setupContent (element);
				}
			},
			onOk: function() {
				var dialog = this;
				var gdiframe = dialog.element;
				
				var style = gdiframe.getAttribute("style");
				gdiframe.setAttribute ("style", style ? style : 'border: none;');
				
				var extraStyles = {},
					extraAttributes = {};
				dialog.commitContent (gdiframe, extraStyles, extraAttributes);

				//get src file extension and add extClass
				var re = /(?:\.([^.]+))?$/;
				var ext = re.exec(dialog.getValueOf('tab-general', 'src'))[1];
				var extClass = ext ? ' gd_'+ ext.toLowerCase() : '';
				
				var newFakeImage = editor.createFakeElement( gdiframe, 'cke_gdiframe' + extClass, editor.docsembedder_type, true );
				newFakeImage.setAttributes( extraAttributes );
				newFakeImage.setStyles( extraStyles );

				if ( dialog.insertMode ){
					editor.insertElement (newFakeImage);
				}else{
					newFakeImage.replace( dialog.fakeImage );
					editor.getSelection().selectElement( newFakeImage );
				}
			}
		};
	});
} )();