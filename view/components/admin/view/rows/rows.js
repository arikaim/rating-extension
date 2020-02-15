'use strict';

$(document).ready(function() {
    safeCall('ratingView',function(obj) {
        obj.initRows();
    },true);     
});