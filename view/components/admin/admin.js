/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com 
 */
'use strict';

function RatingControlPanel() {
  
    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/rating/admin/delete/' + uuid,onSuccess,onError);          
    };

    this.update = function(data, onSuccess, onError) {
        return arikaim.put('/api/rating/admin/update',data,onSuccess,onError);          
    };

    this.init = function() {    
        arikaim.ui.tab();        
    };
}

var ratingControlPanel = new RatingControlPanel();

arikaim.component.onLoaded(function() {
    ratingControlPanel.init();
});