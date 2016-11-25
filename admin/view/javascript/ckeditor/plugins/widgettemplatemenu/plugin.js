CKEDITOR.plugins.add( 'widgettemplatemenu', {
    requires: 'menu',

    defaults : {
        name: 'accordion',
        count: 3,
        activePanel: 1,
        multiExpand: false
    },

    init: function( editor ) {
        
        // Set the default button info based on installed plugins
        var buttonData = {};
        // @todo: make these if statement work
        if (editor.plugins.widgetcommon != undefined) {
            buttonData.widgetcommonBox = 'Insert box';
            buttonData.widgetcommonQuotebox = 'Insert quote box';
        }
        if (editor.plugins.widgetbootstrap != undefined) {
            buttonData.widgetbootstrapLeftCol = 'Insert left column template';
            buttonData.widgetbootstrapRightCol = 'Insert right column template';
            buttonData.widgetbootstrapTwoCol = 'Insert two column template';
            buttonData.widgetbootstrapThreeCol = 'Insert three column template';
            buttonData.widgetbootstrapAlert = 'Insert Alert box';
        }
        if (editor.commands.oembed != undefined) {
            buttonData.oembed = 'Insert media';
        }
        if (editor.commands.codeSnippet != undefined) {
            buttonData.codeSnippet = 'Insert code snippet';
        }
        if (editor.commands.leaflet != undefined) {
            buttonData.leaflet = 'Insert map';
        }

        // Get the enabled menu items from editor.config
        if (editor.config.widgettemplatemenuButtons != undefined) {
            var config = editor.config.widgettemplatemenuButtons.split(',');
            var buttons = {};
            for (var i = 0; i < config.length; i++) {
                buttons[config[i]] = buttonData[config[i]];
            }
        }
        else {
            var buttons = buttonData;
        }
        
        // Build the list of menu items
        var items =  {};
        for(var key in buttons) {
            items[key] = {
                label: buttons[key],
                command: key,
                group: 'widgettemplatemenu',
                icon: key
            }
        }

        // Items must belong to a group.
        editor.addMenuGroup( 'widgettemplatemenu' );
        editor.addMenuItems( items );

        editor.ui.add( 'WidgetTemplateMenu', CKEDITOR.UI_MENUBUTTON, {
            label: 'Insert Template',
            icon: this.path + 'icons/widgettemplatemenu.png' ,
            onMenu: function() {
                // You can control the state of your commands live, every time
                // the menu is opened.
                return {
                    widgetcommonBox: editor.commands.widgetcommonBox == undefined ? false : editor.commands.widgetcommonBox.state,
                    widgetcommonQuotebox: editor.commands.widgetcommonQuotebox == undefined ? false : editor.commands.widgetbootstrapLeftCol.state,
                    widgetbootstrapLeftCol: editor.commands.widgetbootstrapLeftCol == undefined ? false : editor.commands.widgetbootstrapLeftCol.state,
                    widgetbootstrapRightCol: editor.commands.widgetbootstrapRightCol == undefined ? false : editor.commands.widgetbootstrapRightCol.state,
                    widgetbootstrapTwoCol: editor.commands.widgetbootstrapTwoCol == undefined ? false : editor.commands.widgetbootstrapTwoCol.state,
                    widgetbootstrapThreeCol: editor.commands.widgetbootstrapThreeCol == undefined ? false : editor.commands.widgetbootstrapThreeCol.state,
                    widgetbootstrapAlert: editor.commands.widgetbootstrapAlert == undefined ? false : editor.commands.widgetbootstrapAlert.state,
                    widgetbootstrapAccordion: editor.commands.widgetbootstrapAccordion == undefined ? false : editor.commands.widgetbootstrapAccordion.state,
                    oembed: editor.commands.oembed == undefined ? false : editor.commands.oembed.state,
                    codeSnippet: editor.commands.codeSnippet == undefined ? false : editor.commands.codeSnippet.state,
                    leaflet: editor.commands.leaflet == undefined ? false : editor.commands.leaflet.state,
                    FontAwesome: editor.commands.FontAwesome == undefined ? false : editor.commands.FontAwesome.state
                };
            }
        } );
        
    }


} );