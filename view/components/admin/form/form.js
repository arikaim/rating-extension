'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.addRules("#tags_form",{
        inline: false,
        fields: {
            title: {
            identifier: "word",      
                rules: [{
                    type: "minLength[2]"       
                }]
            }
        }
    });   
});