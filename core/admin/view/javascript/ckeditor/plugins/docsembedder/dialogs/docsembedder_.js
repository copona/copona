/*
Embedded document PDF, Office and many others format
via Google Docs Viewer iFrame
Author -vito-
http://opencartmodding.com/extensions/docsembedder
*/
CKEDITOR.dialog.add( 'docsEmbedderDialog', function( editor ) {
	var commonLang = editor.lang.common;
		//docsembedderLang = editor.lang.docsembedder;
    return {
        title: 'Docs embede properties',
        minWidth: 500,
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
								validate: CKEDITOR.dialog.validate.notEmpty( "Source field cannot be empty." ),
								setup: function (element) {
									var decodedurl = decodeURIComponent(element.getAttribute("src").replace('http://docs.google.com/viewer?embedded=true&url=',''));
									this.setValue (decodedurl);
								},
								commit: function (element) {
									element.setAttribute ("src", 'http://docs.google.com/viewer?embedded=true&url=' + encodeURIComponent( this.getValue() ) );
								}
							},
							{
								type: 'button',
								id: 'browse',
								//align: 'right',
								label: commonLang.browseServer,
								//hidden: false,
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
								style: 'width:100%'
							},
							{
								type: 'text',
								id: 'height',
								label: commonLang.height,
								labelLayout: 'vertical',
								style: 'width:100%'
							}
						]
					},
					{
						type: 'text',
						id: 'title',
						label: 'Explanation',
						validate: CKEDITOR.dialog.validate.notEmpty( "Explanation field cannot be empty." ),
						setup: function (element) {
							this.setValue (element.getAttribute("title"));
						},
						commit: function (element) {
							element.setAttribute ("title", this.getValue());
						}
					}
                ]
            },
            {
                id: 'tab-adv',
                label: 'Advanced Settings',
                elements: [
					{
						type: 'text',
						id: 'id',
						label: 'Id',
						setup: function (element) {
							this.setValue (element.getAttribute ("id"));
						},
						commit: function (element) {
							var id = this.getValue();
							if (id)
								element.setAttribute ('id', id);
							else if (!this.insertMode)
								element.removeAttribute('id');
						}
					}
                ]
            }
        ],
		onShow: function() {
					
					/*var fakeImage = this.getSelectedElement();
					if ( fakeImage && fakeImage.data( 'cke-real-element-type' ) && fakeImage.data( 'cke-real-element-type' ) == 'iframe' ) {
						this.fakeImage = fakeImage;

						var iframeNode = editor.restoreRealElement( fakeImage );
						this.iframeNode = iframeNode;
					}*/

			var selection = editor.getSelection();
			var element = selection.getStartElement();
			if (element && element.data( 'cke-real-element-type' ) && element.data( 'cke-real-element-type' ) == editor.docsembedder_type){
				element = editor.restoreRealElement( element );
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
			
			var extraStyles = {},
				extraAttributes = {};
			dialog.commitContent (gdiframe, extraStyles, extraAttributes);

			// Refresh the fake image.
			//предположим, тут я определяю формат файла и в зависимости от него добавляю класс
			var newFakeImage = editor.createFakeElement( gdiframe, 'cke_gdiframe gd_xls', editor.docsembedder_type, true );
			newFakeImage.setAttributes( extraAttributes );
			newFakeImage.setStyles( extraStyles );

			if ( dialog.insertMode ){
				editor.insertElement (newFakeImage);
			}
				
			/*
			abbr.setAttribute ('title', dialog.getValueOf('tab-basic', 'title'));
			abbr.setText (dialog.getValueOf ('tab-basic', 'abbr'));
			
			var id = dialog.getValueOf ('tab-adv', 'id');
			if (id)	abbr.setAttribute('id',id);
			editor.insertElement(abbr);
			*/
		}
    };
});