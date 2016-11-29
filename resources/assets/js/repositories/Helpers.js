var Vue = require('vue');
var $ = require('jquery');
require('sugar');
var moment = require('moment');
require('sweetalert2');
require('tooltipster');
var requests = require('./Requests');
var arrays = require('./Arrays');

module.exports = {

    //Request methods
    get: requests.get,
    post: requests.post,
    put: requests.put,
    delete: requests.delete,

    //Array methods
    findById: arrays.findById,
    findIndexById: arrays.findIndexById,
    deleteById: arrays.deleteById,

    /**
     *
     * @param data
     * @param status
     * @param response
     */
    handleResponseError: function (data, status, response) {
        store.hideLoading();
        $.event.trigger('response-error', [data, status, response]);
        $.event.trigger('hide-loading');
    },

    /**
     *
     * @param object
     */
    clone: function (object) {
        return JSON.parse(JSON.stringify(object));
    },

    /**
     *
     * @param boolean
     * @returns {number}
     */
    convertBooleanToInteger: function (boolean) {
        if (boolean) {
            return 1;
        }
        return 0;
    },

    /**
     *
     * @param number
     * @returns {*}
     */
    addZeros: function (number) {
        if (number < 10) {
            return '0' + number;
        }

        return number;
    },

    /**
     *
     * @param number
     * @param howManyDecimals
     * @returns {number}
     */
    roundNumber: function (number, howManyDecimals) {
        if (!howManyDecimals) {
            return Math.round(number);
        }

        var multiplyAndDivideBy = Math.pow(10, howManyDecimals);
        return Math.round(number * multiplyAndDivideBy) / multiplyAndDivideBy;
    },

    /**
     *
     */
    tooltips: function () {
        var width = $(window).width();
        // Trigger on click rather than hover for small screens
        var trigger = width < 800 ? 'click' : 'hover';

        $('.tooltipster').tooltipster({
            theme: 'tooltipster-punk',
            //Animation duration for in and out
            animationDuration: [1000, 500],
            trigger: trigger,
            side: 'right',
            functionInit: function(instance, helper){

                var $origin = $(helper.origin),
                    dataOptions = $origin.attr('data-tooltipster');

                if(dataOptions){

                    dataOptions = JSON.parse(dataOptions);

                    $.each(dataOptions, function(name, option){
                        instance.option(name, option);
                    });
                }
            }
        });
    },
};