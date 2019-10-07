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
        var component = arikaim.component.get('rating::admin');
        var remove_message = component.getProperty('messages.remove.content');
      
        paginator.init('rating_rows');

    
        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(remove_message,{ title: title });
            modal.confirmDelete({ 
                title: component.getProperty('messages.remove.title'),
                description: message
            },function() {
                rating.delete(uuid,function(result) {
                    $('#' + uuid).remove();                             
                });
            });
        });
    };
}

var ratingView = new RatingView();

arikaim.page.onReady(function() {
    ratingView.init();   
});