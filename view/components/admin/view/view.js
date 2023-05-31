/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com 
*/
'use strict';

function RatingView() {
    var self = this;
  
    this.init = function() {
        paginator.init('rating_rows');
        this.loadMessages('rating::admin');
    };

    this.initRows = function() {
        arikaim.ui.button('.delete-rating-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });
            modal.confirmDelete({ 
                title: self.getMessage('remove.title'),
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
 
            arikaim.page.loadContent({
                id: 'rating_logs',
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

var ratingView = createObject(RatingView,ControlPanelView);

arikaim.component.onLoaded(function() {
    ratingView.init();   
    ratingView.initRows();   
});