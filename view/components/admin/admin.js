/**
 *  Arikaim
 *  @version    1.0  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license.html
 *  http://www.arikaim.com
 * 
 *  Extension: Rating
 *  Component: rating:admin
 */

function RatingControlPanel() {
    var self = this;

    this.delete = function(uuid,onSuccess,onError) {
        return arikaim.delete('/api/rating/admin/delete/' + uuid,onSuccess,onError);          
    };

    this.add = function(data,onSuccess,onError) {
        return arikaim.post('/api/rating/admin/delete/',data, onSuccess,onError);          
    };

    this.loadAddTag = function(parent_id, language) {
        arikaim.ui.setActiveTab('#add_tag','.tags-tab-item')   
        arikaim.page.loadContent({
            id: 'tags_content',
            component: 'tags::admin.add',
            params: { language: language }
        });          
    };

    this.init = function() {    
       
    };
}

var ratingControlPanel = new RatingControlPanel();

arikaim.page.onReady(function() {
    ratingControlPanel.init();
});