/**
 *  Arikaim
 *  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 * 
*/

function RatingLogs() {
    var self = this;

    this.init = function() {
        paginator.init('rating_logs');
    };

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/rating/admin/logs/delete/' + uuid,onSuccess,onError);          
    };

    this.initRows = function() {
        var component = arikaim.component.get('rating::admin');
        var removeMessage = component.getProperty('messages.logs.content');

        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            modal.confirmDelete({ 
                title: component.getProperty('messages.remove.title'),
                description: removeMessage
            },function() {
                self.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#' + uuid);                           
                });
            });
        });

        arikaim.ui.button('.view-rating',function(element) {
            var type = $(element).attr('type');
            var uuid = $(element).attr('uuid');

            arikaim.ui.setActiveTab('#view_rating');

            arikaim.page.loadContent({
                id: 'tab_content',
                component: 'rating::admin.view',
                params: { type: type, uuid: uuid }
            }); 
            
        });
    };
}

var ratingLogs = new RatingLogs();

arikaim.page.onReady(function() {
    ratingLogs.init();   
});