'use strict';

arikaim.component.onLoaded(function() {
    safeCall('ratingLogs',function(obj) {
        obj.initRows();
    },true);         
});