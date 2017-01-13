/**
 * @license MIT 
 *
 * Creato by Webz Ray
 */
CKEDITOR.dialog.add( 'wenzgmapDialog', function( editor ) {

    return {
        title: 'Insert Google Map',
        minWidth: 400,
        minHeight: 75,
        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        type: 'text',
                        id: 'addressStr',
                        label: 'Please enter your central map address'
                    },
                    {
                        type: 'text',
                        id: 'mapWidth',
                        label: 'Map Width (px)',
                        style:'width:25%;',
                    },
                    {
                        type: 'text',
                        id: 'mapHeight',
                        label: 'Map Height (px)',
                        style: 'width:25%;',
                    }

                ]
            }
        ],
        onOk: function() {
            var dialog = this;
            var url = dialog.getValueOf('tab-basic', 'addressStr').trim();
            var mapWidth = dialog.getValueOf('tab-basic', 'mapWidth').trim();
            var mapHeight = dialog.getValueOf('tab-basic', 'mapHeight').trim();
			/*var regExURL=/v=([^&$]+)/i;
			var id_video=url.match(regExURL);
			
			if(id_video==null || id_video=='' || id_video[0]=='' || id_video[1]=='')
				{
				alert("URL invalid! Try a sample like a\n\n\t http://www.youtube.com/watch?v=abcdef \n\n Thank you!");
				return false;
				}
            */
            var oTag = editor.document.createElement( 'iframe' );
			
            oTag.setAttribute('width', mapWidth);
            oTag.setAttribute('height', mapHeight);
			oTag.setAttribute('src', '//maps.google.com/maps?q=' + url + '&num=1&t=m&ie=UTF8&z=14&output=embed');
			oTag.setAttribute( 'frameborder', '0' );
			oTag.setAttribute('scrolling', 'no');

            editor.insertElement( oTag );
        }
    };
});