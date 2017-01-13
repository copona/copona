CKEDITOR.dialog.add('osem_googlemaps', function(editor) {
	return {
		title: 'Google Maps',
		minWidth: 350,
		minHeight: 100,

		onShow: function () {
			this.setupContent(this.getSelectedElement());
		},

		onOk: function () {
			var area = this.getValueOf('info', 'area').replace(/\"/g, "");
			var zoom = this.getValueOf('info', 'zoom');
			var width = this.getValueOf('info', 'width').replace(/\D/g, "");
			var height = this.getValueOf('info', 'height').replace(/\D/g, "");
			var mapType = this.getValueOf('info', 'map_type');

			var s = '<img src="http://maps.google.com/maps/api/staticmap?center=' + area.replace(/\s/g, "+") + '&markers=color:red|' + area.replace(/\s/g, "+") + '&zoom=' + zoom + '&size=' + width + 'x' + height + '&sensor=false&maptype=' + mapType + '" alt="' + area + '"'
				+ ' cke_googlemaps_area="' + area + '"'
				+ ' cke_googlemaps_zoom="' + zoom + '"'
				+ ' cke_googlemaps_width="' + width + '"'
				+ ' cke_googlemaps_height="' + height + '"'
				+ ' cke_googlemaps_map_type="' + mapType + '"'
				+ ' onClick="window.open(\'https://www.google.com/maps/place/' + encodeURIComponent(area) + '\', \'_blank\')"'
				+ ' style="cursor: pointer"'
				+ '/>';
			editor.insertHtml(s, 'unfiltered_html');
		},

		contents: [{
			id: 'info',
			label: 'Google Maps',
			title: 'Google Maps',
			elements: [
				{
					id: 'area',
					label: 'Area',
					type: 'text',
					setup: function(element) {
						this.setValue(element.getAttribute('cke_googlemaps_' + this.id));
					}
				},
				{
					id: 'zoom',
					label: 'Zoom',
					type: 'select',
					setup: function(element) {
						this.setValue(element.getAttribute('cke_googlemaps_' + this.id));
					},
					labelLayout: 'horizontal',
					'default': '14',
					items: [
						['20 - closest', '20'],
						['19', '19'],
						['18', '18'],
						['17', '17'],
						['16', '16'],
						['15', '15'],
						['14', '14'],
						['13', '13'],
						['12', '12'],
						['11', '11'],
						['10', '10'],
						['9', '9'],
						['8', '8'],
						['7 - furthest', '7']
					]
				},
				{
					id: 'width',
					label: 'Width',
					type: 'text',
					setup: function(element) {
						this.setValue(element.getAttribute('cke_googlemaps_' + this.id));
					},
					labelLayout: 'horizontal',
					width: '40px',
					'default': 300
				},
				{
					id: 'height',
					label: 'Height',
					type: 'text',
					setup: function(element) {
						this.setValue(element.getAttribute('cke_googlemaps_' + this.id));
					},
					labelLayout: 'horizontal',
					width: '40px',
					'default': 300
				},
				{
					id: 'map_type',
					label: 'Map Type',
					type: 'select',
					setup: function(element) {
						this.setValue(element.getAttribute('cke_googlemaps_' + this.id));
					},
					labelLayout: 'horizontal',
					'default': 'hybrid',
					items: [
						['roadmap', 'roadmap'],
						['satellite', 'satellite'],
						['hybrid', 'hybrid'],
						['terrain', 'terrain']
					]
				}
			]
		}]
	};
});