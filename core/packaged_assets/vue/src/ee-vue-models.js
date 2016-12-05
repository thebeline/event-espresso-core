var Vue = require('vue');
var VueResource = require('vue-resource');

Vue.use(VueResource);

Vue.config.debug = true;

(function( window, undefined ) {
    'use strict';

    /**
     * initialize EEVue
     */
    window.eejs= window.eejs || {};
    eejs.Vue = eejs.Vue || Vue;

})(window);

/**
 * Notes:
 *
 * With vue I don't think we'll have things like a "model" but instead we'll have a resource.
 *
 * Setting up EE resources on the Vue.resource property
 * (see https://github.com/pagekit/vue-resource/blob/master/docs/resource.md)
 * I think I'll want to dynamically setup a custom actions object from the rest schema for EE (maybe load
 * that via wp_localize_scripts())?
 * Then I can add that to the vue.resource object and it should feasibly be available for any VUE instance.
 *
 * Another thing I may want to think of is that I create components for each resource (and they can thus
 * build on each other.  These can be dynamically build on an extended Vue object to have the defaults
 * (see http://vuejs.org/v2/guide/instance.html).
 *
 * var Event = Vue.extend({
 *  data: {
 *       //default model properties
 *  },
 *  methods: {
 *      save :
 *  }
 * })
 */