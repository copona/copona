//Google QR-Code generator plugin by zmmaj from zmajsoft-team
//blah... version 1.1.
//problems? write to zmajsoft@zmajsoft.com

CKEDITOR.plugins.add( 'qrc',
{
	init: function( editor )
	{
		editor.addCommand( 'qrc', new CKEDITOR.dialogCommand( 'qrc' ) );
		editor.ui.addButton( 'qrc',
		{
			label: 'Insert a ZS Google QR-Code picture',
			command: 'qrc',
			icon: this.path + 'images/qrc.png'
		} );
 
		CKEDITOR.dialog.add( 'qrc', function( editor )
		{
			return {
				title : 'ZmajSoft QR-Code Picture generator',
				minWidth : 400,
				minHeight : 200,
				contents :
				[
					{
						id : 'qrc_general',
						label : 'QR Settings',
						elements :
						[
							{
								type : 'html',
								html : 'This dialog window lets you create and embed into text simple Google QR-Code Picture. '
							},
							{
								type : 'text',
								id : 'txt',
								label : 'Enter ANY text, code or mix',
								validate : CKEDITOR.dialog.validate.notEmpty( 'Can NOT be empty.' ),
								required : true,
								commit : function( data )
								{
									data.txt = this.getValue();
								}
							},
					
														{
								type : 'text',
								id : 'siz',
								label : 'Enter picture size ( eg 300)',
								validate : CKEDITOR.dialog.validate.notEmpty( 'Can NOT be empty.' ),
								required : true,
								commit : function( data )
								{
									data.siz= this.getValue();
								}
							},


		           	{
								type : 'html',
							html : 'If you have problems Email to zmajsoft@zmajsoft.com </br> <a href="www.zmajsoft.com" target="_blank">zmmaj</a> from zmajSoft-team'
							}
						]
					}
				],
				onOk : function()
				{
			var dialog = this,
						data = {},
						link = editor.document.createElement( 'a' );
					this.commitContent( data );

					editor.insertHtml('<img src="https://chart.googleapis.com/chart?cht=qr&chs='+data.siz+'x'+data.siz+ '&chl='+data.txt+'&choe=UTF-8 &chld=H |4"/>');
				}
			};
		} );
	}
} );