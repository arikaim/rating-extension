/**
 *  Arikaim
 *  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 * 
*/

function RatingView() {
    var self = this;

    this.init = function() {
        paginator.init('rating_rows');
    };

    this.initRows = function() {
        var component = arikaim.component.get('rating::admin');
        var removeMessage = component.getProperty('messages.remove.content');

        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(removeMessage,{ title: title });
            modal.confirmDelete({ 
                title: component.getProperty('messages.remove.title'),
                description: message
            },function() {
                ratingControlPanel.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#' + uuid);                            
                });
            });
        });

        arikaim.ui.button('.view-logs',function(element) {
            var type = $(element).attr('type');
            var referenceId = $(element).attr('reference-id');

            arikaim.ui.setActiveTab('#view_logs');

            arikaim.page.loadContent({
                id: 'tab_content',
                component: 'rating::admin.logs',
                params: { 
                    type: type,
                    reference_id: referenceId 
                }
            });  
        });
    };
}

var ratingView = new RatingView();

arikaim.page.onReady(function() {
    ratingView.init();   
});