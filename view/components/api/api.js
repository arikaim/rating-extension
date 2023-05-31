'use strict';

function RatingApi() {

    this.add = function(id, type, value, onSuccess, onError) {       
        return arikaim.post('/api/rating/add',{
            id: id,
            type: type,
            value: value
        },onSuccess,onError);          
    };
}

var ratingApi = new RatingApi();