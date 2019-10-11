/**
 *  Arikaim
 *  @version    1.0  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license.html
 *  http://www.arikaim.com
 * 
 *  Extension: Rating
 *  Component: ratging::admin.logs
*/

function RatingLogs() {
    var self = this;

    this.init = function() {
        paginator.init('rating_logs');
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
                self.delete(uuid,function(result) {
                    $('#' + uuid).remove();                             
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