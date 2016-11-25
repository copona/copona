/*

	This file is a part of simplebuttion project.

	Copyright (C) Thanh D. Dang <thanhdd.it@gmail.com>

	simplebuttion is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	simplebuttion is distributed in the hope that it will be useful, but
	WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/


CKEDITOR.dialog.add( 'simplebuttonDialog', function( editor ) {
	return {
		title: 'Simple Button',
		minWidth: 400,
		minHeight: 200,
		contents: [
			{
				id: 'tab-basic',
				elements: [
					{
						type: 'text',
						id: 'button-text',
						label: 'Text',
						validate: CKEDITOR.dialog.validate.notEmpty( "Text field cannot be empty." ),
						setup: function( element, preview ) {
							this.preview_button = preview;
							this.setValue( element.getText() );
						},
						commit: function( element ) {
							element.setText( this.getValue() );
						},
						onChange: function() {
							this.preview_button.setText( this.getValue() );
						}
					},
					{
						type: 'text',
						id: 'button-url',
						label: 'URL',
						setup: function( element ) {
							this.setValue( element.getAttribute( "href" ) );
						},
						commit: function( element ) {
							element.setAttribute( "href", this.getValue() );
						}
					},
					{
						type: 'text',
						id: 'font-size',
						label: 'Font Size (px)',
						validate: CKEDITOR.dialog.validate.regex( /^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/, "Font size is not valid." ),
						setup: function( element, preview ) {
							this.preview_button = preview;
							this.setValue( element.getStyle('font-size').split('px')[0] );
						},
						commit: function( element ) {
							element.setStyle( 'font-size', this.getValue() + 'px' );
						},
						onChange: function() {
							var valid = this.getValue().match( /^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/ );
							if (valid) {
								this.preview_button.setStyle( 'font-size', this.getValue() + 'px' );
							}
						}
					},
					{
						type: 'text',
						id: 'border-radius',
						label: 'Border Radius (px)',
						validate: CKEDITOR.dialog.validate.regex( /^\+?(0|[1-9]\d*)$/, "Border Radius is not valid." ),
						setup: function( element, preview ) {
							this.preview_button = preview;
							this.setValue( element.getStyle('border-radius').split('px')[0] );
						},
						commit: function( element ) {
							element.setStyle( 'border-radius', this.getValue() + 'px' );
						},
						onChange: function() {
							var valid = this.getValue().match( /^\+?(0|[1-9]\d*)$/ );
							if (valid) {
								this.preview_button.setStyle( 'border-radius', this.getValue() + 'px' );
							}
						}
					},
					{
						type : 'html',
						html : '<p>Background</p><input id="color-text-input" style="width:20%;float:left" class="cke_dialog_ui_input_text" type="text"><div id="custom-color-button" style="width:25px; height:25px;background-size:25px;background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAACwVBMVEUFBQUaGhomJiYzMzNAQEBERERISEhLS0tPT09SUlJVVVVYWFhcXFxfX19iYmJlZWVoaGhra2ttbW1wcHBycnJ1dXV3d3d5eXl7e3t9fX1/f3////8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAgIAGBgYGBgZ/f3+AgIB3d3d5eXl7e3t9fX1wcHBycnJ1dXVra2ttbW1iYmJlZWVoaGhcXFxfX19VVVVYWFhPT09SUlJISEhLS0tERERAQEAzMzMmJiYaGhoQEBAHBwdYWNNYqtNYxVhZWdxZsNxZzVlbW9dbrtdbyVteXudeuede2F5gYNtgsttgzmBhYephveph22FjY+BjtuBj0WNkZNlksdlkzGRlZe9lwe9l32VnZ+NnuuNn1WdpaeVpauVpvOVp2GlqavNqxfNqxvNq5Gpubvhuyvhu6G5vb/JvxvJv5G9ycvxyzvxy7HKqWNOq01iuW9eu11uwWdyw3FmxZNmx2WSyYNuy22C2Y+C24GO5Xue55166Z+O642e8aeW8auW85Wm9Yeq96mHBZe/B72XFavPF82rGavPGb/LG8m/G82rKbvjK+G7OcvzO/HLTWFjTWKrTqljT09PXW1vXW67XrlvX19fZZGTZZLHZsWTZ2dnbYGDbYLLbsmDb29vcWVncWbDcsFnc3Nzf39/gY2PgY7bgtmPg4ODh4eHi4uLjZ2fjZ7rjumfj4+Pk5OTlaWnlabzlamnlarzlvGnl5eXm5ubnXl7nXrnnuV7n5+fp6enqYWHqYbzqYb3qvWHq6urr6+vs7Ozt7e3vZWXvZcHvwWXv7+/w8PDx8fHyb2/yb8byxm/y8vLzamrzasXzasbzxWrzxmrz8/P09PT19fX29vb4bm74bsr4ym74+Pj5+fn6+vr7+/v8cnL8cs78znL8/Pz9/f3+/v7///8IFeovAAAARnRSTlMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABwgLDQ4PEhUZGk1eYGZmZ2dnZ2hoaGlpampqa2tsbG1tbm5vcHFzdXZ4cX7MKgAAAZFJREFUOMt9kz1uFEEQhb+e6Z1dz9pgYQlWRg7IEQkQE/KTACcAzgUkcAcgJOQcxIS2Z7an6EdQtSsZazzJp1LVVL9X3ZXuc/OXdZjns3aeMu3P+YJnkLVAc/m0UMqS9OMUYHP324amcrr57ryHRJbg5KH/cfLoKqOgWtm64KCCiyyR1aFhTEpiPQyeGEYEZDqRJdN4AUA/XgJwFPGh+RGSRWsbg1tASZLIyrLj4mM5noIRL3J0eByanwaf7Dx4B6tf1wAHL3/5PLovPUn0ryw6lNUtAErM48/qtserEDlZBWAK//t4GSJVSzZAQ7ippamA5EcUK7UA2Hju6kutToms1uQxu3k0+7h1DTV7oob/bhe7zVbTc/c9bYIvgss27uJzD9C//uR88/GAJNZvLTpYd+Ti9vS5mELDdlgDcI1riaxGKn/joVhSEioWd9GIrDRZuQj/l1c5JZGVUBmiw3/ECzS17v8aFR14F4/4ffDDfjFEVuruzC1Ol0R6sDybX73fo6Wztm1m0qpWlWudblz/f2jHKXM/GqmjAAAAAElFTkSuQmCC); float:left; cursor: pointer;"></div><table id="colors-table" style="margin-top: -44px;right: 88px;position: absolute; z-index: 1; display:none"><tbody><tr style="border-bottom: 1px solid #fff;height: 23px;"><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;width: 23px; background-color: #F44236;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;width: 23px; background-color: #DD5561;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;width: 23px; background-color: #E91D62;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;width: 23px; background-color: #363F46;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;width: 23px; background-color: #9C26B0;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;width: 23px; background-color: #6739B6;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;width: 23px; background-color: #2A80B9;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;width: 23px; background-color: #3E50B4;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;width: 23px; background-color: #2095F2;"></td></tr><tr style="border-bottom: 1px solid #fff;height: 23px;"><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;width: 23px; background-color: #02A8F4;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #01BBD4;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #019587;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #27AE61;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #1BBC9B;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #4BAF4F;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #8BC24A;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #CCDB38;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #FFE93B;"></td></tr><tr style="border-bottom: 1px solid #fff;height: 23px;"><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #F39C11;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #FEC107;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #FF9700;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #FF5521;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #795549;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #9D9D9D;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #607C8A;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-color: #8F44AD;"></td><td class="color-column" style="cursor:pointer;border-left: 1px solid #fff;background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAADFBMVEXMyszMzszU0tT8/vwq5swEAAAAZElEQVR4Ae3SwQ2EAAgF0Rm2/543Ro04NcjtceH/BEYHh981NSKK96LGc+Ze1Awy8JyIT+pzIkbRFapmUFao+uCwQtU73OjU8AqD1rz7M3X6W0P71+nPbPfEwRrS3/r7h+8ftv/vfQe1Hir3FQAAAABJRU5ErkJggg==);background-size:23px;background-repeat:no-repeat;"></td></tr></tbody></table>'
					},
					{
						type : 'html',
						html : '<p>Preview</p><div id="previewDiv" style="background-color: #ccc;border: 1px solid #bbb;padding: 10px;text-align: center;"><a id="preview-button"></a></div>',
						setup: function( element ) {
							var document = this.getElement().getDocument();
							var color_columns = document.find('.color-column').$;
							for (var i = 0; i < color_columns.length; i++) {
								var style = color_columns[i].getAttribute('style');
								var background_color = 'transparent';
								var border_color = '#fff';
								if (style.indexOf('background-color') > -1) {
									background_color = style.split('background-color: ')[1].split(';')[0];
									border_color = background_color;
								}
								color_columns[i].setAttribute('onclick', 'document.getElementById("color-text-input").value = "'+background_color+'";var preview_button = document.getElementById("preview-button");var color = document.getElementById("color-text-input").value;preview_button.style["background-color"] = "'+background_color+'";preview_button.style["border"] = "1px solid '+border_color+'";document.getElementById("colors-table").style.display = "none"');
							}
							var custom_color_button = document.getById( 'custom-color-button' );
							custom_color_button.setAttribute( 'onclick', 'var colors_table = document.getElementById("colors-table"); if(colors_table.style.display == "none") colors_table.style.display = "block"; else colors_table.style.display = "none";' );
							var color_text_input = document.getById( 'color-text-input' );
							color_text_input.setAttribute( 'value', element.getAttribute('style').split('background-color:')[1].split(';')[0] );
							color_text_input.setAttribute( 'onchange', 'var preview_button = document.getElementById("preview-button");var color = document.getElementById("color-text-input").value;preview_button.style["background-color"] = color;preview_button.style["border"] = "1px solid " + color;' );
							var preview_button = document.getById( 'preview-button' );
							preview_button.setAttribute( "style", element.getAttribute( "style" ) );
							preview_button.setText( element.getText() );
						},
						commit: function( element ) {
							var document = this.getElement().getDocument();
							var background = document.getById( 'color-text-input' ).$.value;
							var boder_color = background;
							if (background == 'transparent') {
								 boder_color = '#fff';
							}
							element.setStyle( "background-color", background );
							element.setStyle( "border", '1px solid ' + boder_color );
						}
					}
				]
			}
		],

		onShow: function() {
			var selection = editor.getSelection();
			var element = selection.getStartElement();

			if ( !element || !element.hasClass('simple-button-plugin') ) {
				element = editor.document.createElement( 'a' );
				element.setAttribute('class', 'simple-button-plugin');
				element.setAttribute('target', '_blank');
				var style_button = 'display:inline-block;background-color:#27AE61;border:1px solid #27AE61;color:#fff !important;padding:5px 10px;border-radius:5px;font-size:14px;text-decoration: none !important; cursor: pointer;';
				element.setAttribute( "style", style_button );
				element.setText( 'Unsubscribe' );
				this.insertMode = true;
			}
			else
				this.insertMode = false;

			this.element = element;

			var document = this.getElement().getDocument();
			var preview_button = document.getById( 'preview-button' );
			this.setupContent( this.element, preview_button );
		},

		onOk: function() {
			var dialog = this;
			var simple_btn = this.element;
			this.commitContent( simple_btn );

			if ( this.insertMode )
				editor.insertElement( simple_btn );
		}
	};
});
