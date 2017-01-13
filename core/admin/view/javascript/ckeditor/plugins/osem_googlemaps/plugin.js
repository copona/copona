/*

Simple Google Maps plugin for CKeditor 4

version 1.1

by Osem Websystems

*/

(function() {
	CKEDITOR.plugins.add('osem_googlemaps', {
		init: function(editor) {

			var path = this.path;

			/* button command */

			function addButtonCommand(buttonName, label, commandName) {
				editor.addCommand(commandName, new CKEDITOR.dialogCommand(commandName));
				editor.ui.addButton(buttonName, {
					label: label,
					command: commandName,
					icon: path + 'img/' + commandName + '.gif'
				});
				CKEDITOR.dialog.add(commandName, path + commandName + '.js');
			}

			addButtonCommand('osem_googlemaps', 'Google Maps', 'osem_googlemaps');

			/* context menu */

			if (editor.addMenuItems) {
				editor.addMenuGroup('osem_googlemaps');

				editor.addMenuItems({
					osem_googlemaps: {
						label: 'Edit Map',
						command: 'osem_googlemaps',
						group: 'osem_googlemaps',
						icon: path + 'img/googlemaps.gif'
					}
				});
			}

			if (editor.contextMenu) editor.contextMenu.addListener(function(element, selection) {
				if (element && element.is('img') && element.getAttribute('src').indexOf('://maps.google.com') > -1) return {
					osem_googlemaps: CKEDITOR.TRISTATE_OFF
				};	else return null;
			});

			/* double click */

			editor.on('doubleclick', function(ev) {
				var el = ev.data.element;
				if (el.getName() == 'img' && el.getAttribute('src').indexOf('://maps.google.com') > -1) ev.data.dialog = 'osem_googlemaps';
				else return null;
			});
		}
	});
})();