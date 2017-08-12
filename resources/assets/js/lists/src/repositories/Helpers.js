require('sugar');
var moment = require('moment');
require('sweetalert2');
// require('tooltipster');
import requests from './Requests'
import arrays from './Arrays'

export default {

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
     */
    closePopup: function ($event, that, routeToGoTo) {
        if ($($event.target).hasClass('popup-outer')) {
            // that.mutableShowPopup = false;
            that.$emit('update:showPopup', false);
            // store.set(false, 'showItemPopup');
            that.$router.push(routeToGoTo);
        }
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
     * commenting out for now because it was erroring saying .tooltipster is not a function
     */
    // tooltips: function () {
    //     var width = $(window).width();
    //     // Trigger on click rather than hover for small screens
    //     var trigger = width < 800 ? 'click' : 'hover';
    //
    //     $('.tooltipster').tooltipster({
    //         theme: 'tooltipster-punk',
    //         //Animation duration for in and out
    //         animationDuration: [1000, 500],
    //         trigger: trigger,
    //         side: 'right',
    //         functionInit: function(instance, helper){
    //
    //             var $origin = $(helper.origin),
    //                 dataOptions = $origin.attr('data-tooltipster');
    //
    //             if(dataOptions){
    //
    //                 dataOptions = JSON.parse(dataOptions);
    //
    //                 $.each(dataOptions, function(name, option){
    //                     instance.option(name, option);
    //                 });
    //             }
    //         }
    //     });
    // },

    /**
     *
     * @param dateAndTime
     * @returns {*}
     */
    convertToDateTime: function (dateAndTime) {
        if (!dateAndTime || dateAndTime === '') {
            return null;
        }

        var dateTime = Date.create(dateAndTime).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');

        if (dateTime == 'Invalid Date') {
            //Only add my shortcuts if the date is invalid for Sugar
            if (dateAndTime == 't') {
                dateAndTime = 'today';
            }
            else if (dateAndTime == 'to') {
                dateAndTime = 'tomorrow';
            }
            else if (dateAndTime == 'y') {
                dateAndTime = 'yesterday';
            }

            dateTime = Date.create(dateAndTime).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');
        }

        return dateTime;
    },

    /**
     *
     * @param dateTime
     * @param format - format to convert to
     * @returns {*}
     */
    convertFromDateTime: function (dateTime, format) {
        format = format || 'ddd DD MMM YYYY';
        return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format(format);
    },

    /**
     * If url is /items/:2, return 2
     * @param that
     * @returns {*}
     */
    getIdFromUrl: function () {
        //For some reason $route.params was undefined.
        //if (that.$route.params.id) {
        //    return that.$route.params.id.slice(1);
        //}
        // var path = that.$route.path;
        // if (!VueRouter.app) return false;

        //commenting this for now because router is undefined. Fix later.
        // var path = router.app._route.path;
        // var index = path.indexOf(':');
        // if (index != -1) {
        //     return path.slice(index+1);
        // }
        return false;
    }
}