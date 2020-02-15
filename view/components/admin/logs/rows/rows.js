'use strict';

$(document).ready(function() {
    safeCall('ratingLogs',function(obj) {
        obj.initRows();
    },true);         
});