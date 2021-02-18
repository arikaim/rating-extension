'use strict';

arikaim.component.onLoaded(function() {
    safeCall('ratingView',function(obj) {
        obj.initRows();
    },true);     
});