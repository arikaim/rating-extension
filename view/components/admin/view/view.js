/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com 
*/
'use strict';

function RatingView() {
    var self = this;
    this.messages = null;

    this.loadMessages = function() {
        if (isObject(this.messages) == true) {
            return;
        }

        arikaim.component.loadProperties('rating::admin',function(params) {           
            self.messages = params.messages;
        }); 
    };

    this.init = function() {
        paginator.init('rating_rows');
        this.loadMessages();
    };

    this.initRows = function() {
    
        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(self.messages.remove.content,{ title: title });
            modal.confirmDelete({ 
                title: self.messages.remove.title,
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
            var ratingUuid = $(element).attr('rating-uuid');
 
            arikaim.ui.setActiveTab('#view_logs');

            arikaim.page.loadContent({
                id: 'tab_content',
                component: 'rating::admin.logs',
                params: { 
                    type: type,
                    rating_uuid: ratingUuid,
                    reference_id: referenceId 
                }
            },function(result) {
                ratingLogs.initRows();
            });  
        });
    };
}

var ratingView = new RatingView();

arikaim.component.onLoaded(function() {
    ratingView.init();   
    ratingView.initRows();   
});