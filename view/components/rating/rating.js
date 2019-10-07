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

    this.add = function(id, type, value , onSuccess, onError) {
        var data = {
            id: id,
            type: type,
            value: value
        };
        return arikaim.post('/api/rating/add',data, onSuccess,onError);          
    };

    this.init = function() {
        $('.rating').rating({
            initialRating: 2,
            maxRating: 4, 
            disable: false,
            onRate: function(value) {
                console.log(value);
            }
        });

    };
}

var rating = new Rating();
