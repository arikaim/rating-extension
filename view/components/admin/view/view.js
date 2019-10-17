/**
 *  Arikaim
 *  @version    1.0  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license.html
 *  http://www.arikaim.com
 * 
 *  Extension: Rating
 *  Component: ratging::admin.view
*/

function RatingView() {
    var self = this;

    this.init = function() {
        paginator.init('rating_rows');
    };

    this.initRows = function() {
        var component = arikaim.component.get('rating::admin');
        var remove_message = component.getProperty('messages.remove.content');

        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(remove_message,{ title: title });
            modal.confirmDelete({ 
                title: component.getProperty('messages.remove.title'),
                description: message
            },function() {
                ratingControlPanel.delete(uuid,function(result) {
                    $('#' + uuid).remove();                             
                });
            });
        });

        arikaim.ui.button('.view-logs',function(element) {
            var type = $(element).attr('type');
            var reference_id = $(element).attr('reference-id');

            arikaim.ui.setActiveTab('#view_logs');

            arikaim.page.loadContent({
                id: 'tab_content',
                component: 'rating::admin.logs',
                params: { type: type, reference_id: reference_id }
            });  
        });
    };
}

var ratingView = new RatingView();

arikaim.page.onReady(function() {
    ratingView.init();   
});