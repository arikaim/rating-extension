/**
 *  Arikaim
 *  @version    1.0  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license.html
 *  http://www.arikaim.com
 * 
 *  Extension: Rating
 */

function Rating() {
    var self = this;

    this.add = function(id, type, value, onSuccess, onError) {
        var data = {
            id: id,
            type: type,
            value: value
        };
        return arikaim.post('/api/rating/add',data,onSuccess,onError);          
    };

    this.updateLabels = function(summary) {
        $('.rating-summary').html(summary);
    };

    this.init = function() {

        $('.rating').rating({             
            onRate: function(value) {
                var id = $(this).attr('reference-id');
                var type = $(this).attr('type');
                var rating = this;

                self.add(id,type,value,function(result) {                   
                    self.updateLabels(result.average);                   
                    $(rating).rating('disable');
                });
            }
        });

    };
}

var rating = new Rating();

$(document).ready(function() {
    rating.init();
});