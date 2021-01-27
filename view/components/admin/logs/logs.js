/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com 
*/
'use strict';

function RatingLogs() {
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
        paginator.init('rating_logs',{
            name: 'rating::admin.logs.rows' 
        },'rating.logs');
        
        this.loadMessages();
    };

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/rating/admin/logs/delete/' + uuid,onSuccess,onError);          
    };

    this.initRows = function() {
        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(self.messages.logs.content,{ title: title });

            modal.confirmDelete({ 
                title: self.messages.logs.title,
                description: message
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
                params: { 
                    type: type,
                    uuid: uuid 
                }
            }); 
            
        });
    };
}

var ratingLogs = new RatingLogs();

$(document).ready(function() {
    ratingLogs.init();   
    ratingLogs.initRows();
});