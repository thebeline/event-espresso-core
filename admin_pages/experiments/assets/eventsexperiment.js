(function(){
    'use strict';
    //modify the components we're using
    eejs.api.components.Event.template = eejs.templates.event;
    eejs.api.EventCollection = Vue.extend(eejs.api.components.EventCollection);
    eejs.api.eeEvents = new eejs.api.EventCollection({
        el: '#app',
        components : {
            'event': eejs.api.components.Event
        }
    });
})();