// Register the plugin within the editor.
CKEDITOR.plugins.add('listgroup', {

    lang: 'en',
    requires: 'widget',
    icons: 'listgroup',

    init: function (editor) {

        editor.ui.addButton('ListGroup', {
            label: 'Create a list group',
            command: 'listgroup',
            toolbar: 'bootstrap,4'
        });

        var widget = {
            allowedContent: '*',

            defaults: (function () {
                return {
                    groupId: 'list-group-container-' + Math.round(Math.random(100000, 999999) * 1000000),
                    items: 10,
                    badge: true
                }
            })(),

            // Minimum HTML which is required by this widget to work.
            requiredContent: 'div(list-group-container)',

            editables: {
                container: 'div.list-group-container'
            },

            parts: {
                container:  'div.list-group-container',
                group:      'ul.list-group',
                item:       'li.list-group-item'
            },

            // Define the template of a new Simple Box widget.
            // The template will be used when creating new instances of the Simple Box widget.
            template: '<div class="list-group-container"><ul class="list-group"><li class="list-group-item"></li></ul></div>',

            // Check the elements that need to be converted to widgets.
            //
            // Note: The "element" argument is an instance of http://docs.ckeditor.com/#!/api/CKEDITOR.htmlParser.element
            // so it is not a real DOM element yet. This is caused by the fact that upcasting is performed
            // during data processing which is done on DOM represented by JavaScript objects.
            upcast: function (element) {
                // Return "true" (that element needs to converted to a Simple Box widget)
                // for all <div> elements with a "jumbotron" class.
                return element.name == 'div' && element.hasClass('list-group-container');
            },

            // When a widget is being initialized, we need to read the data ("align" and "width")
            // from DOM and set it by using the widget.setData() method.
            // More code which needs to be executed when DOM is available may go here.
            init: function () {
                if (!this.element.getId()) { // New carousels don't have an id yet.
                    this.element.$.id = this.data.groupId; // There is no exposed method for setId().
                }
                else {
                    this.setData('groupId', this.element.getId());
                }
            }
        };

        // Register the listgroup widget.
        editor.widgets.add('listgroup', widget);
    }
});
