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


CKEDITOR.plugins.add( 'simplebutton', {
	init: function( editor ) {
		editor.addCommand( 'simplebutton', new CKEDITOR.dialogCommand( 'simplebuttonDialog' ) );
		editor.ui.addButton( 'simplebutton', {
			label: 'Simple Button',
			command: 'simplebutton',
			icon: this.path + 'images/simplebutton.png'
		});
		editor.on( 'doubleclick', function( evt ) {
			var element = evt.data.element;
			if ( element.hasClass('simple-button-plugin') ) {
				evt.data.dialog = 'simplebuttonDialog';
			}
		});

		CKEDITOR.dialog.add( 'simplebuttonDialog', this.path + 'dialogs/simplebutton.js' );
	}
});
