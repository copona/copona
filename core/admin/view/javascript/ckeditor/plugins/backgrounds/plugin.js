/*
 * @file Background plugin for CKEditor
 * Copyright (C) 2011-13 Alfonso Martínez de Lizarrondo
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *	http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *	http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *	http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 */
(function() {
"use strict";

// A placeholder just to notify that the plugin has been loaded
CKEDITOR.plugins.add( 'backgrounds',
{
	// Translations, available at the end of this file, without extra requests
//	lang : [ 'en', 'es', 'nl', 'ru', 'uk' ],

	init : function( editor )
	{
		// v 4.1 filters
		if (editor.addFeature)
		{
			editor.addFeature( {
				name : 'background image',
				allowedContent: {
					'table td th': {
						propertiesOnly: true,
						attributes: 'background',
						styles: 'background-repeat,background-position'
					}
				}
			} );
		}

		// For compatibility with older versions of CKEditor
		if (!editor.getColorFromDialog)
		{
			/**
			 * Open up color dialog and to receive the selected color.
			 *
			 * @param {Function} callback The callback when color dialog is closed
			 * @param {String} callback.color The color value received if selected on the dialog.
			 * @param [scope] The scope in which the callback will be bound.
			 * @member CKEDITOR.editor
			 */
			editor.getColorFromDialog = function( callback, scope ) {
				var onClose = function( evt ) {
					releaseHandlers( this );
					var color = evt.name == 'ok' ? this.getValueOf( 'picker', 'selectedColor' ) : null;
					callback.call( scope, color );
				};
				var releaseHandlers = function( dialog ) {
					dialog.removeListener( 'ok', onClose );
					dialog.removeListener( 'cancel', onClose );
				};
				var bindToDialog = function( dialog ) {
					dialog.on( 'ok', onClose );
					dialog.on( 'cancel', onClose );
				};

				editor.execCommand( 'colordialog' );

				if ( editor._.storedDialogs && editor._.storedDialogs.colordialog )
					bindToDialog( editor._.storedDialogs.colordialog );
				else {
					CKEDITOR.on( 'dialogDefinition', function( e ) {
						if ( e.data.name != 'colordialog' )
							return;

						var definition = e.data.definition;

						e.removeListener();
						definition.onLoad = CKEDITOR.tools.override( definition.onLoad,
							function( orginal ) {
								return function() {
									bindToDialog( this );
									definition.onLoad = orginal;
									if ( typeof orginal == 'function' )
										orginal.call( this );
								};
							} );
					} );
				}
			};
		}

		// It doesn't add commands, buttons or dialogs, it doesn't do anything here
	} //Init
} );


	// * @param {Function} setup Setup function which returns a value instead of setting it.
	// * @returns {Function} A function to be used in dialog definition.
	function setupCells( setup ) {
		return function( cells ) {
			var fieldValue;

			// Compatibility for previous versions of cell dialog as well as the table dialog (array vs single element)
			if (cells.getAttribute) {
				fieldValue = setup( cells );
			} else {
				fieldValue = setup( cells[ 0 ] );

				// If one of the cells would have a different value of the
				// property, set the empty value for a field.
				for ( var i = 1; i < cells.length; i++ ) {
					if ( setup( cells[ i ] ) !== fieldValue ) {
						fieldValue = null;
						break;
					}
				}
			}

			// Setting meaningful or empty value only makes sense
			// when setup returns some value. Otherwise, a *default* value
			// is used for that field.
			if ( typeof fieldValue != 'undefined' ) {
				this.setValue( fieldValue );

				// The only way to have an empty select value in Firefox is
				// to set a negative selectedIndex.
				if ( CKEDITOR.env.gecko && this.type == 'select' && !fieldValue )
					this.getInputElement().$.selectedIndex = -1;
			}
		};
	}

// This is the real code of the plugin
CKEDITOR.on( 'dialogDefinition', function( ev )
	{
		// Take the dialog name and its definition from the event data.
		var dialogName = ev.data.name,
			dialogDefinition = ev.data.definition,
			editor = ev.editor,
			colorDialog = editor.plugins.colordialog,
			tabName = '';

		// Check if it's one of the dialogs that we want to modify and note the proper tab name.
		if ( dialogName == 'table' || dialogName == 'tableProperties' )
			tabName = 'advanced';

		if ( dialogName == 'cellProperties' )
		{
			tabName = 'info';
			dialogDefinition.minHeight += 80;
		}

		// Not one of the managed dialogs.
		if ( tabName == '' )
			return;

		// Get a reference to the tab.
		var tab = dialogDefinition.getContents( tabName ),
			lang = editor.lang.backgrounds;

		if (!tab)
			return;

		// The text field
		var textInput =  {
				type : 'text',
				label : lang.label,
				id : 'background',
				setup : setupCells(function( selectedElement )
				{
					return selectedElement.getAttribute( 'background' );
				}),
				commit : function( data, selectedElement )
				{
					var element = selectedElement || data,
						value = this.getValue();
					if ( value )
						element.setAttribute( 'background', value );
					else
						element.removeAttribute( 'background' );
				}
			};

		// File browser button
		var browseButton =  {
				type : 'button',
				id : 'browse',
				hidden : 'true',
				filebrowser :
				{
					action : 'Browse',
					target: tabName + ':background',
					url: editor.config.filebrowserImageBrowseUrl || editor.config.filebrowserBrowseUrl
				},
				label : editor.lang.common.browseServer,
				requiredContent : textInput.requiredContent
			};

		// The position field
		var backgroundPosition = {
			type: 'select',
			label: lang.position,
			id: 'backgroundPosition',
			items:
			[
				[lang.left_top,'left top'],
				[lang.left_center,'left center'],
				[lang.left_bottom,'left bottom'],
				[lang.center_top,'center top'],
				[lang.center_center,'center center'],
				[lang.center_bottom,'center bottom'],
				[lang.right_top,'right top'],
				[lang.right_center,'right center'],
				[lang.right_bottom,'right bottom']
			],
			setup: setupCells(function (selectedElement) {
				return selectedElement.getStyle('background-position');
			}),
			onChange: function() {
				var stylesInput = this.getDialog().getContentElement('advanced', 'advStyles');

				if (stylesInput) {
					stylesInput.updateStyle('background-position', this.getValue());
				}
			},
			commit: function (data, selectedElement) {
				var element = selectedElement || data,
					value = this.getValue(),
					oBackground = this.getDialog().getContentElement(tabName, "background"),
					background = oBackground && oBackground.getValue();

				if (value && background)
					element.setStyle('background-position', value);
				else
					element.removeStyle('background-position'); // it doesn't really work for the table
			}
		};

		// The repeat select field
		var backgroundRepeat = {
			type: 'select',
			label: lang.repeat,
			id: 'backgroundRepeat',
			items:
			[
				[ lang.repeatBoth, '' ],
				[ lang.repeatX, 'repeat-x' ],
				[ lang.repeatY, 'repeat-y' ],
				[ lang.repeatNone, 'no-repeat' ]
			],
			setup: setupCells(function (selectedElement) {
				return selectedElement.getStyle('background-repeat');
			}),
			onChange: function() {
				var stylesInput = this.getDialog().getContentElement('advanced', 'advStyles');

				if (stylesInput) {
					stylesInput.updateStyle('background-repeat', this.getValue());
				}
			},
			commit: function (data, selectedElement) {
				var element = selectedElement || data,
					value = this.getValue(),
					oBackground = this.getDialog().getContentElement(tabName, "background"),
					background = oBackground && oBackground.getValue();

				if (value && background)
					element.setStyle('background-repeat', value);
				else
					element.removeStyle('background-repeat'); // it doesn't really work for the table
			}
		};

		// The background-color field
		var backgroundColor = {
			type: 'hbox',
			padding: 0,
			widths: ['30px', '20px'],
			id: 'bgColor',
			children: [
				{
					type: 'text',
					id: 'backgroundColor',
					label: lang.color,
					'default': '',
					setup: setupCells(function(element) {
						var bgColorAttr = element.getAttribute('backgroundColor'),
							bgColorStyle = element.getStyle('background-color');

						return bgColorStyle || bgColorAttr;
					}),
					onChange: function() {
						var stylesInput = this.getDialog().getContentElement('advanced', 'advStyles');

						if (stylesInput) {
							stylesInput.updateStyle('background-color', this.getValue());
						}
					},
					commit: function(data, selectedElement) {
						var element = selectedElement || data,
							value = this.getValue();

						if (value){
							element.setStyle('background-color', value);
						}else{
							element.removeStyle('background-color');
						}
					}
				},
				{
					type: 'button',
					id: 'bgColorChoose',
					label: lang.chooseColor,
					onLoad: function() {
						// Stick the element to the bottom (#5587)
						this.getElement().getParent().setStyle('vertical-align', 'middle');
					},
					onClick: function(element) {
						editor.getColorFromDialog(function(color) {
							var stylesInput = this.getDialog().getContentElement('advanced', 'advStyles');
							var input = this.getDialog().getContentElement('advanced', 'backgroundColor');
							input.setValue(color);

							if (stylesInput && color) {
								stylesInput.updateStyle('background-color', color);
							}

							input.focus();
						}, this);
					}
				}
			]
		};

		// The Attachment select field
		var backgroundAttachment = {
			type: 'select',
			label: lang.attachment,
			id: 'backgroundAttachment',
			items:
					[
						[lang.attachmentScroll, ''],
						[lang.attachmentFixed, 'fixed'],
						[lang.attachmentLocal, 'local']
					],
			setup: setupCells(function(selectedElement) {
				return selectedElement.getStyle('background-attachment');
			}),
			onChange: function() {
				var stylesInput = this.getDialog().getContentElement('advanced', 'advStyles');

				if (stylesInput) {
					stylesInput.updateStyle('background-attachment', this.getValue());
				}
			},
			commit: function(data, selectedElement) {
				var element = selectedElement || data,
						value = this.getValue(),
						oBackground = this.getDialog().getContentElement(tabName, "background"),
						background = oBackground && oBackground.getValue();

				if (value && background)
					element.setStyle('background-attachment', value);
				else
					element.removeStyle('background-attachment'); // it doesn't really work for the table
			}
		};

		// Enabled/disabled automatically in 4.1 by ACF
		if (dialogName == 'cellProperties')
		{
			textInput.requiredContent = 'td[background];th[background]';
			backgroundPosition.requiredContent = 'td[background];th[background]';
			backgroundRepeat.requiredContent = 'td[background];th[background]';
			backgroundColor.requiredContent = 'td[background];th[background]';
		}
		else
		{
			textInput.requiredContent = 'table[background]';
			backgroundPosition.requiredContent = 'table[background]';
			backgroundRepeat.requiredContent = 'table[background]';
			backgroundColor.requiredContent = 'table[background]';
		}

		// Add the elements to the dialog
		if (tabName == 'advanced')
		{
			// Two rows
			tab.add(textInput);
			tab.add(browseButton);
			tab.add({
				type: 'hbox',
				widths: ['', '100px'],
				children: [backgroundPosition, backgroundRepeat ]
			});
			tab.add({
				type: 'hbox',
				widths: ['50%', '50%'],
				children: [backgroundAttachment, backgroundColor]
			});
		}
		else
		{
			// In the cell dialog add it as a single row
			browseButton.style = 'display:inline-block;margin-top:10px;';
			tab.add({
				type : 'hbox',
				widths: [ '', '100px'],
				children : [ textInput, browseButton],
				requiredContent : textInput.requiredContent
			});
			tab.add({
				type: 'hbox',
				widths: ['', '100px'],
				children: [backgroundPosition, backgroundRepeat]
			});
		}

	// inject this listener before the one from the fileBrowser plugin so there are no problems with the new fields.
	}, null, null, 9 );


// Translations
	function addTranslation(lang, texts) {
		// V3 vs V4
		if (CKEDITOR.skins)
			CKEDITOR.plugins.setLang( 'backgrounds', lang, { backgrounds : texts } );
		else
			CKEDITOR.plugins.setLang( 'backgrounds', lang,	texts );
	}

	// English
	addTranslation('en', {
		label	: 'Background image',
		position : 'Background position',
		repeat: 'Background repeat',
		color: 'Background Color',
		chooseColor: 'Choose',
		attachment: 'Background Attachment',
		attachmentScroll: 'Scroll',
		attachmentFixed: 'Fixed',
		attachmentLocal: 'Local',
		repeatBoth : 'Repeat',
		repeatX : 'Horizontally',
		repeatY : 'Verticaly',
		repeatNone : 'None',
		left_top : 'Left Top',
		left_center :'Left Center',
		left_bottom : 'Left Bottom',
		center_top : 'Center Top',
		center_center : 'Center Center',
		center_bottom :'Center Bottom',
		right_top : 'Right Top',
		right_center :' Right Center',
		right_bottom : 'Right Bottom'
	});

	// Spanish
	addTranslation('es', {
		label	: 'Imagen de fondo',
		position : 'Posición del fondo',
		repeat: 'Repetición del fondo',
		color: 'Color de fondo',
		chooseColor: 'Elegir',
		attachment: 'Desplazamiento',
		attachmentScroll: 'Scroll',
		attachmentFixed: 'Fixed',
		attachmentLocal: 'Local',
		repeatBoth: 'Repetir',
		repeatX: 'Horizontalmente',
		repeatY: 'Verticalmente',
		repeatNone: 'Ninguno',
		left_top: 'Izquierda arriba',
		left_center: 'Izquierda centro',
		left_bottom: 'Izquierda abajo',
		center_top: 'Centro arriba',
		center_center: 'Centro centro',
		center_bottom: 'Centro abajo',
		right_top: 'Derecha arriba',
		right_center: ' Derecha centro',
		right_bottom: 'Derecha abajo'
	});

	// Dutch
    addTranslation('nl', {
        label	: 'Achtergrond afbeelding',
        position : 'Achtergrond positie',
        repeat: 'Achtergrond herhaling',
  		color: 'Background Color',
		chooseColor: 'Choose',
		attachment: 'Background Attachment',
		attachmentScroll: 'Scroll',
		attachmentFixed: 'Fixed',
		attachmentLocal: 'Local',
		repeatBoth : 'Herhalen',
        repeatX : 'Horizontaal',
        repeatY : 'Verticaal',
        repeatNone : 'Geen',
        left_top : 'Links Boven',
        left_center :'Links Midden',
        left_bottom : 'Links Onder',
        center_top : 'Centreren Boven',
        center_center : 'Centreren Midden',
        center_bottom :'Centreren Onder',
        right_top : 'Rechts Boven',
        right_center :' Rechts Midden',
        right_bottom : 'Rechts Onder'
    });

	// Russian
	addTranslation('ru', {
		label: 'Фоновое изображение',
		position: 'Позиция фона',
		repeat: 'Повтор фона',
		color: 'Цвет фона',
		chooseColor: 'Выбрать',
		attachment: 'Привязка фона',
		attachmentScroll: 'Прокручивать со страницей (scroll)',
		attachmentFixed: 'Фиксированная (fixed)',
		attachmentLocal: 'Прокрутка с элементом (local)',
		repeatBoth: 'Повтор',
		repeatX: 'Горизонтально',
		repeatY: 'Вертикально',
		repeatNone: 'Без повтора',
		left_top: 'Слева сверху',
		left_center: 'Слева в центре',
		left_bottom: 'Слева снизу',
		center_top: 'По центру, сверху',
		center_center: 'По центру',
		center_bottom:'По центру, снизу',
		right_top: 'Справа сверху',
		right_center: 'Справа в центре',
		right_bottom: 'Справа снизу'
	});

	// Ukranian
	addTranslation('uk', {
		label: 'Фонове зображення',
		position: 'Позиція фону',
		repeat: 'Повтор фону',
		color: 'Колір фону',
		chooseColor: 'Обрати',
		attachment: 'Прив\'язка фона',
		attachmentScroll: 'Прокручувати зі сторінкою (scroll)',
		attachmentFixed: 'Фіксована (fixed)',
		attachmentLocal: 'Прокручувати із елементом (local)',
		repeatBoth: 'Повтор',
		repeatX: 'Горизонтально',
		repeatY: 'Вертикально',
		repeatNone: 'Без повтору',
		left_top: 'Зліва згори',
		left_center: 'Зліва посередині',
		left_bottom: 'Зліва знизу',
		center_top: 'По центру, зверху',
		center_center: 'По центру',
		center_bottom:'По центру, знизу',
		right_top: 'Справа згори',
		right_center: 'Справа посередині',
		right_bottom: 'Справа знизу'
	});
})();
