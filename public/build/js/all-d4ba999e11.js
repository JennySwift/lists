/*!
 * The Final Countdown for jQuery v2.1.0 (http://hilios.github.io/jQuery.countdown/)
 * Copyright (c) 2015 Edson Hilios
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):a(jQuery)}(function(a){"use strict";function b(a){if(a instanceof Date)return a;if(String(a).match(g))return String(a).match(/^[0-9]*$/)&&(a=Number(a)),String(a).match(/\-/)&&(a=String(a).replace(/\-/g,"/")),new Date(a);throw new Error("Couldn't cast `"+a+"` to a date object.")}function c(a){var b=a.toString().replace(/([.?*+^$[\]\\(){}|-])/g,"\\$1");return new RegExp(b)}function d(a){return function(b){var d=b.match(/%(-|!)?[A-Z]{1}(:[^;]+;)?/gi);if(d)for(var f=0,g=d.length;g>f;++f){var h=d[f].match(/%(-|!)?([a-zA-Z]{1})(:[^;]+;)?/),j=c(h[0]),k=h[1]||"",l=h[3]||"",m=null;h=h[2],i.hasOwnProperty(h)&&(m=i[h],m=Number(a[m])),null!==m&&("!"===k&&(m=e(l,m)),""===k&&10>m&&(m="0"+m.toString()),b=b.replace(j,m.toString()))}return b=b.replace(/%%/,"%")}}function e(a,b){var c="s",d="";return a&&(a=a.replace(/(:|;|\s)/gi,"").split(/\,/),1===a.length?c=a[0]:(d=a[0],c=a[1])),1===Math.abs(b)?d:c}var f=[],g=[],h={precision:100,elapse:!1};g.push(/^[0-9]*$/.source),g.push(/([0-9]{1,2}\/){2}[0-9]{4}( [0-9]{1,2}(:[0-9]{2}){2})?/.source),g.push(/[0-9]{4}([\/\-][0-9]{1,2}){2}( [0-9]{1,2}(:[0-9]{2}){2})?/.source),g=new RegExp(g.join("|"));var i={Y:"years",m:"months",n:"daysToMonth",w:"weeks",d:"daysToWeek",D:"totalDays",H:"hours",M:"minutes",S:"seconds"},j=function(b,c,d){this.el=b,this.$el=a(b),this.interval=null,this.offset={},this.options=a.extend({},h),this.instanceNumber=f.length,f.push(this),this.$el.data("countdown-instance",this.instanceNumber),d&&("function"==typeof d?(this.$el.on("update.countdown",d),this.$el.on("stoped.countdown",d),this.$el.on("finish.countdown",d)):this.options=a.extend({},h,d)),this.setFinalDate(c),this.start()};a.extend(j.prototype,{start:function(){null!==this.interval&&clearInterval(this.interval);var a=this;this.update(),this.interval=setInterval(function(){a.update.call(a)},this.options.precision)},stop:function(){clearInterval(this.interval),this.interval=null,this.dispatchEvent("stoped")},toggle:function(){this.interval?this.stop():this.start()},pause:function(){this.stop()},resume:function(){this.start()},remove:function(){this.stop.call(this),f[this.instanceNumber]=null,delete this.$el.data().countdownInstance},setFinalDate:function(a){this.finalDate=b(a)},update:function(){if(0===this.$el.closest("html").length)return void this.remove();var b,c=void 0!==a._data(this.el,"events"),d=new Date;b=this.finalDate.getTime()-d.getTime(),b=Math.ceil(b/1e3),b=!this.options.elapse&&0>b?0:Math.abs(b),this.totalSecsLeft!==b&&c&&(this.totalSecsLeft=b,this.elapsed=d>=this.finalDate,this.offset={seconds:this.totalSecsLeft%60,minutes:Math.floor(this.totalSecsLeft/60)%60,hours:Math.floor(this.totalSecsLeft/60/60)%24,days:Math.floor(this.totalSecsLeft/60/60/24)%7,daysToWeek:Math.floor(this.totalSecsLeft/60/60/24)%7,daysToMonth:Math.floor(this.totalSecsLeft/60/60/24%30.4368),totalDays:Math.floor(this.totalSecsLeft/60/60/24),weeks:Math.floor(this.totalSecsLeft/60/60/24/7),months:Math.floor(this.totalSecsLeft/60/60/24/30.4368),years:Math.abs(this.finalDate.getFullYear()-d.getFullYear())},this.options.elapse||0!==this.totalSecsLeft?this.dispatchEvent("update"):(this.stop(),this.dispatchEvent("finish")))},dispatchEvent:function(b){var c=a.Event(b+".countdown");c.finalDate=this.finalDate,c.elapsed=this.elapsed,c.offset=a.extend({},this.offset),c.strftime=d(this.offset),this.$el.trigger(c)}}),a.fn.countdown=function(){var b=Array.prototype.slice.call(arguments,0);return this.each(function(){var c=a(this).data("countdown-instance");if(void 0!==c){var d=f[c],e=b[0];j.prototype.hasOwnProperty(e)?d[e].apply(d,b.slice(1)):null===String(e).match(/^[$A-Z_][0-9A-Z_$]*$/i)?(d.setFinalDate.call(d,e),d.start()):a.error("Method %s does not exist on jQuery.countdown".replace(/\%s/gi,e))}else new j(this,b[0],b[1])})}});
/**
 * Version: 1.0 Alpha-1 
 * Build Date: 13-Nov-2007
 * Copyright (c) 2006-2007, Coolite Inc. (http://www.coolite.com/). All rights reserved.
 * License: Licensed under The MIT License. See license.txt and http://www.datejs.com/license/. 
 * Website: http://www.datejs.com/ or http://www.coolite.com/datejs/
 */
TimeSpan=function(days,hours,minutes,seconds,milliseconds){this.days=0;this.hours=0;this.minutes=0;this.seconds=0;this.milliseconds=0;if(arguments.length==5){this.days=days;this.hours=hours;this.minutes=minutes;this.seconds=seconds;this.milliseconds=milliseconds;}
else if(arguments.length==1&&typeof days=="number"){var orient=(days<0)?-1:+1;this.milliseconds=Math.abs(days);this.days=Math.floor(this.milliseconds/(24*60*60*1000))*orient;this.milliseconds=this.milliseconds%(24*60*60*1000);this.hours=Math.floor(this.milliseconds/(60*60*1000))*orient;this.milliseconds=this.milliseconds%(60*60*1000);this.minutes=Math.floor(this.milliseconds/(60*1000))*orient;this.milliseconds=this.milliseconds%(60*1000);this.seconds=Math.floor(this.milliseconds/1000)*orient;this.milliseconds=this.milliseconds%1000;this.milliseconds=this.milliseconds*orient;return this;}
else{return null;}};TimeSpan.prototype.compare=function(timeSpan){var t1=new Date(1970,1,1,this.hours(),this.minutes(),this.seconds()),t2;if(timeSpan===null){t2=new Date(1970,1,1,0,0,0);}
else{t2=new Date(1970,1,1,timeSpan.hours(),timeSpan.minutes(),timeSpan.seconds());}
return(t1>t2)?1:(t1<t2)?-1:0;};TimeSpan.prototype.add=function(timeSpan){return(timeSpan===null)?this:this.addSeconds(timeSpan.getTotalMilliseconds()/1000);};TimeSpan.prototype.subtract=function(timeSpan){return(timeSpan===null)?this:this.addSeconds(-timeSpan.getTotalMilliseconds()/1000);};TimeSpan.prototype.addDays=function(n){return new TimeSpan(this.getTotalMilliseconds()+(n*24*60*60*1000));};TimeSpan.prototype.addHours=function(n){return new TimeSpan(this.getTotalMilliseconds()+(n*60*60*1000));};TimeSpan.prototype.addMinutes=function(n){return new TimeSpan(this.getTotalMilliseconds()+(n*60*1000));};TimeSpan.prototype.addSeconds=function(n){return new TimeSpan(this.getTotalMilliseconds()+(n*1000));};TimeSpan.prototype.addMilliseconds=function(n){return new TimeSpan(this.getTotalMilliseconds()+n);};TimeSpan.prototype.getTotalMilliseconds=function(){return(this.days()*(24*60*60*1000))+(this.hours()*(60*60*1000))+(this.minutes()*(60*1000))+(this.seconds()*(1000));};TimeSpan.prototype.get12HourHour=function(){return((h=this.hours()%12)?h:12);};TimeSpan.prototype.getDesignator=function(){return(this.hours()<12)?Date.CultureInfo.amDesignator:Date.CultureInfo.pmDesignator;};TimeSpan.prototype.toString=function(format){function _toString(){if(this.days()!==null&&this.days()>0){return this.days()+"."+this.hours()+":"+p(this.minutes())+":"+p(this.seconds());}
else{return this.hours()+":"+p(this.minutes())+":"+p(this.seconds());}}
function p(s){return(s.toString().length<2)?"0"+s:s;}
var self=this;return format?format.replace(/d|dd|HH|H|hh|h|mm|m|ss|s|tt|t/g,function(format){switch(format){case"d":return self.days();case"dd":return p(self.days());case"H":return self.hours();case"HH":return p(self.hours());case"h":return self.get12HourHour();case"hh":return p(self.get12HourHour());case"m":return self.minutes();case"mm":return p(self.minutes());case"s":return self.seconds();case"ss":return p(self.seconds());case"t":return((this.hours()<12)?Date.CultureInfo.amDesignator:Date.CultureInfo.pmDesignator).substring(0,1);case"tt":return(this.hours()<12)?Date.CultureInfo.amDesignator:Date.CultureInfo.pmDesignator;}}):this._toString();};var TimePeriod=function(years,months,days,hours,minutes,seconds,milliseconds){this.years=0;this.months=0;this.days=0;this.hours=0;this.minutes=0;this.seconds=0;this.milliseconds=0;if(arguments.length==2&&arguments[0]instanceof Date&&arguments[1]instanceof Date){var date1=years.clone();var date2=months.clone();var temp=date1.clone();var orient=(date1>date2)?-1:+1;this.years=date2.getFullYear()-date1.getFullYear();temp.addYears(this.years);if(orient==+1){if(temp>date2){if(this.years!==0){this.years--;}}}else{if(temp<date2){if(this.years!==0){this.years++;}}}
date1.addYears(this.years);if(orient==+1){while(date1<date2&&date1.clone().addDays(date1.getDaysInMonth())<date2){date1.addMonths(1);this.months++;}}
else{while(date1>date2&&date1.clone().addDays(-date1.getDaysInMonth())>date2){date1.addMonths(-1);this.months--;}}
var diff=date2-date1;if(diff!==0){var ts=new TimeSpan(diff);this.days=ts.days;this.hours=ts.hours;this.minutes=ts.minutes;this.seconds=ts.seconds;this.milliseconds=ts.milliseconds;}
return this;}};

/*! timer.jquery 0.4.2 2015-08-04*/!function(a){function b(){p=setInterval(d,v.updateFrequency),t=!0}function c(){clearInterval(p),t=!1}function d(){s=g()-q,e(),u&&s%u===0&&(v.callback(),v.repeat||(u=v.duration=null),v.countdown&&(v.countdown=!1))}function e(){var a=s;v.countdown&&u>0&&(a=u-s),r[w](i(a)),r.data("seconds",a)}function f(){r.on("focus",function(){l()}),r.on("blur",function(){var a,b=r[w]();b.indexOf("sec")>0?s=Number(b.replace(/\ssec/g,"")):b.indexOf("min")>0?(b=b.replace(/\smin/g,""),a=b.split(":"),s=Number(60*a[0])+Number(a[1])):b.match(/\d{1,2}:\d{2}:\d{2}/)&&(a=b.split(":"),s=Number(3600*a[0])+Number(60*a[1])+Number(a[2])),m()})}function g(){return Math.round((new Date).getTime()/1e3)}function h(a){var b,c=0,d=Math.floor(a/60);return a>=3600&&(c=Math.floor(a/3600)),a>=3600&&(d=Math.floor(a%3600/60)),10>d&&c>0&&(d="0"+d),b=a%60,10>b&&(d>0||c>0)&&(b="0"+b),{hours:c,minutes:d,seconds:b}}function i(a){var b="",c=h(a);if(v.format){var d=[{identifier:"%h",value:c.hours,pad:!1},{identifier:"%m",value:c.minutes,pad:!1},{identifier:"%s",value:c.seconds,pad:!1},{identifier:"%H",value:parseInt(c.hours),pad:!0},{identifier:"%M",value:parseInt(c.minutes),pad:!0},{identifier:"%S",value:parseInt(c.seconds),pad:!0}];b=v.format,d.forEach(function(a){b=b.replace(new RegExp(a.identifier.replace(/([.*+?^=!:${}()|\[\]\/\\])/g,"\\$1"),"g"),a.pad&&a.value<10?"0"+a.value:a.value)})}else b=c.hours?c.hours+":"+c.minutes+":"+c.seconds:c.minutes?c.minutes+":"+c.seconds+" min":c.seconds+" sec";return b}function j(a){if(!isNaN(Number(a)))return a;var b=a.match(/\d{1,2}h/),c=a.match(/\d{1,2}m/),d=a.match(/\d{1,2}s/),e=0;return a=a.toLowerCase(),b&&(e+=3600*Number(b[0].replace("h",""))),c&&(e+=60*Number(c[0].replace("m",""))),d&&(e+=Number(d[0].replace("s",""))),e}function k(){t||(e(),b(),r.data("state",y))}function l(){t&&(c(),r.data("state",z))}function m(){t||(q=g()-s,b(),r.data("state",y))}function n(){q=g(),s=0,r.data("seconds",s),r.data("state",x),u=v.duration}function o(){c(),r.data("plugin_"+B,null),r.data("seconds",null),r.data("state",null),r[w]("")}var p,q,r,s=0,t=!1,u=null,v={seconds:0,editable:!1,restart:!1,duration:null,callback:function(){alert("Time up!"),c()},repeat:!1,countdown:!1,format:null,updateFrequency:1e3},w="html",x="stopped",y="running",z="paused",A=function(b,c){var d;v=a.extend(v,c),r=a(b),s=v.seconds,q=g()-s,r.data("seconds",s),r.data("state",x),d=r.prop("tagName").toLowerCase(),("input"===d||"textarea"===d)&&(w="val"),v.duration&&(u=v.duration=j(v.duration)),v.editable&&f()};A.prototype={start:function(){k()},pause:function(){l()},resume:function(){m()},reset:function(){n()},remove:function(){o()}};var B="timer";a.fn[B]=function(b){return b=b||"start",this.each(function(){a.data(this,"plugin_"+B)instanceof A||a.data(this,"plugin_"+B,new A(this,b));var c=a.data(this,"plugin_"+B);"string"==typeof b&&"function"==typeof c[b]&&c[b].call(c),"object"==typeof b&&c.start.call(c)})}}(jQuery);
var Vue = require('vue');
Vue.config.debug = true;
module.exports = {
    /**
     *
     * @param array
     * @param id
     * @returns {*}
     */
    findById: function (array, id) {
        var index = this.findIndexById(array, id);
        return array[index];
    },

    /**
     *
     * @param array
     * @param id
     * @returns {*}
     */
    findIndexById: function (array, id) {
        // return _.indexOf(array, _.findWhere(array, {id: id}));
        //So it still work if id is a string
        return _.indexOf(array, _.find(array, function (item) {
            return item.id == id;
        }));
    },

    /**
     *
     * @param array
     * @param id
     */
    deleteById: function (array, id) {
        var index = helpers.findIndexById(array, id);
        array = _.without(array, array[index]);

        return array;
    }
};
var moment = require('moment');

module.exports = {

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
        //if (Date.parse(dateAndTime)) {
        //    return Date.parse(dateAndTime).toString('yyyy-MM-dd HH:mm:ss');
        //}
        //
        //return null;
    },

    /**
     *
     * @param dateTime
     * @param format - format to convert to
     * @returns {*}
     */
    convertFromDateTime: function (dateTime, format) {
        format = format || 'ddd DD/MM/YY hh:mma';
        return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format(format);
    }
};
var TimeRepository = require('./TimeRepository');
var DateAndTimeRepository = require('./DateTimeRepository');
var moment = require('moment');

module.exports = {
    /**
     *
     * @param alarm
     * @returns {*}
     */
    formatAlarm: function (alarm) {
        if (!alarm) {
            return false;
        }

        var days = TimeRepository.days;

        for (var i = 0; i < days.length; i++) {
            if (alarm.indexOf(days[i].day) != -1) {
                var match = days[i];
            }
        }

        //Format contains hours, mins, or secs
        if (alarm.indexOf('mins') != -1 || alarm.indexOf('hours') != -1 || alarm.indexOf('secs') != -1 || alarm.indexOf('weeks') != -1 || alarm.indexOf('months') != -1) {
            alarm = TimeRepository.getTimeFromNow(alarm);
        }

        else if (match) {
            //Format contains a day of the week from the days array
            alarm = TimeRepository.getTimeFromDayAndTime(alarm, match);
        }


        //else if (alarm.indexOf('mon') != -1 || alarm.indexOf('mon') != -1) {
        //    alarm = TimeRepository.getTimeFromDayAndTime(alarm);
        //
        //}
        //
        else {
            alarm = Date.parse(alarm).toString('yyyy-MM-dd HH:mm:ss');
        }
        //console.log(alarm);
        return alarm;
    },

    /**
     *
     * @param items
     * @returns {*}
     */
    order: function (items) {
        //Sort first by priority, then by urgency, then by notBefore, then by category name, then by id
        //Reverse is so the more recently added items are on top of the older items
        return _.chain(items).sortBy('id').reverse()
            .sortBy(function (item) {
                return item.category.name;
            })
            .sortBy('notBefore').sortBy('urgency').partition('urgency').flatten().sortBy('priority').value();
    },

    filter: function (items, that) {
        //var that = this;

        //Sort
        //items = _.chain(items).sortBy('id').sortBy('priority').sortBy('urgency').partition('urgency').flatten().value();
        items = filters.order(items);

        //Filter
        return items.filter(function (item) {
            //Title filter
            var filteredIn = item.title.toLowerCase().indexOf(that.filters.title.toLowerCase()) !== -1;

            //Priority filter-at or higher than priority specified
            if (that.filters.minimumPriority && item.priority > that.filters.minimumPriority) {
                filteredIn = false;
            }

            //Priority filter
            if (that.filters.priority && item.priority != that.filters.priority) {
                filteredIn = false;
            }
            //Urgency filter
            else if (that.filters.urgency && item.urgency != that.filters.urgency) {
                filteredIn = false;
            }
            //Urgency out filter
            else if (that.filters.urgencyOut && item.urgency >= that.filters.urgencyOut) {
                filteredIn = false;
            }
            //Category filter
            else if (that.filters.category && item.category_id !== that.filters.category) {
                filteredIn = false;
            }
            //Not before filter (if checked, do not show items with a not before value after the current time)
            else if (that.filters.notBefore && filters.notBeforeTimeIsAfterCurrentTime(item.notBefore, that.currentTime)) {
                filteredIn = false;
            }
            //Not before date filter
            else if (that.filters.notBeforeDate) {
                //Only show items with a not before date on the specified date
                if (!item.notBefore) {
                    filteredIn = false;
                }
                else {
                    var filterDate = DateTimeRepository.convertFromDateTime(DateTimeRepository.convertToDateTime(that.filters.notBeforeDate));
                    var itemDate = DateTimeRepository.convertFromDateTime(item.notBefore);
                    if (filterDate !== itemDate) {
                        filteredIn = false;
                    }
                }
            }

            return filteredIn;
        });
    },

    /**
     *
     * @param notBeforeTime
     * @param currentTime
     * @returns {*}
     */
    notBeforeTimeIsAfterCurrentTime: function (notBeforeTime, currentTime) {
        if (!currentTime) {
            //Doing it like this so that the currentTime is a property on the component,
            //so the filter will run regularly when currentTime is updated in the component
            currentTime = moment();
        }
        return moment(notBeforeTime).isAfter(currentTime);
    },

    /**
     *
     * @param seconds
     * @returns {string}
     */
    timeLeftFilter: function (seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor(seconds / 60) % 60;
        seconds = seconds % 60;

        return this.addZeros(hours) + ':' + this.addZeros(minutes) + ':' + this.addZeros(seconds);
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

};
var Vue = require('vue');
var $ = require('jquery');
require('sugar');
var moment = require('moment');
require('sweetalert2');
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
    }
};
module.exports = {

    /**
     *
     * @returns {boolean}
     */
    shouldFilterBeShownOnPageLoad: function () {
        var width = $(window).width();
        if (width > 1040) {
            return true;
        }
        return false;
    },

    /**
     *
     * @param item
     * @param response
     */
    updateProperties: function (item, response) {
        item.title = response.title;
        item.body = response.body;
        item.parent_id = response.parent_id;
        item.category_id = response.category_id;
        item.category = response.category;
        item.favourite = response.favourite;
        item.alarm = response.alarm;
        item.notBefore = response.notBefore;
        item.recurringUnit = response.recurringUnit;
        item.recurringFrequency = response.recurringFrequency;
        item.priority = response.priority;
        item.urgency = response.urgency;
        item.parent = response.parent;
    },

    /**
     *
     * @param item
     * @returns {{title: *, body: (*|string|Array|HTMLElement), priority: *, favourite: (*|boolean), pinned: (*|boolean), category_id: *, parent_id: (*|null)}}
     */
    setData: function (item, zoomedItem) {
        var data = {
            title: item.title,
            body: item.body,
            priority: item.priority,
            urgency: item.urgency,
            favourite: HelpersRepository.convertBooleanToInteger(item.favourite),
            pinned: item.pinned,
            category_id: item.category.id,
            alarm: false,
            not_before: DateTimeRepository.convertToDateTime(item.notBefore),
            recurring_unit: item.recurringUnit,
            recurring_frequency: item.recurringFrequency
        };

        //So the urgency can be removed
        if (item.urgency === '') {
            data.urgency = false;
        }

        if (item.alarm && item.alarm !== '') {
            //This check is here because if item.alarm was false,
            //I think PHP was getting value 0, making the item have an alarm
            //at '0000-00-00 00:00:00.'
            data.alarm = this.formatAlarm(item.alarm)
        }

        if (!data.pinned) {
            //Make sure it's a boolean for the PHP
            data.pinned = 0;
        }

        if (item.parent) {
            data.parent_id = item.parent.id;
        }
        else if (item.parent_id) {
            data.parent_id = item.parent_id;
        }
        else if (zoomedItem) {
            data.parent_id = zoomedItem.id;
        }
        else if (item.oldParentId && item.parent_id === '') {
            data.parent_id = 'none';
        }
        else {
            data.parent_id = null;
        }

        return data;
    },

    /**
     * If url is /items/:2, return 2
     * @param that
     * @returns {*}
     */
    getIdFromUrl: function (that) {
        //For some reason $route.params was undefined.
        //if (that.$route.params.id) {
        //    return that.$route.params.id.slice(1);
        //}
        var path = that.$route.path;
        var index = path.indexOf(':');
        if (index != -1) {
            return that.$route.path.slice(index+1);
        }
        return false;
    },


    /**
     *
     * @param that
     * @param item
     */
    deleteItem: function (that, item) {
        if (confirm("Are you sure?")) {
            $.event.trigger('show-loading');

            if (item.recurringUnit) {
                //It's a recurring item, so we're updating the not-before time of the item, rather than actually deleting the item
                var data = {
                    updatingNextTimeForRecurringItem: true
                };

                that.$http.put('/api/items/' + item.id, data, function (response) {
                    item.notBefore = response.notBefore;
                    that.showPopup = false;
                    $.event.trigger('provide-feedback', ['Item has been rescheduled, not deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
            }

            else {
                //We are actually deleting the item
                that.$http.delete('/api/items/' + item.id, function (response) {
                    ItemsRepository.deleteJsItem(that, item);
                    that.showPopup = false;
                    $.event.trigger('provide-feedback', ['Item deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
            }
        }
    },

    /**
     *
     * @param item
     */
    deleteJsItem: function (that, item) {
        var parent = ItemsRepository.findParent(that.items, item, false, true);
        var index;
        if (parent) {
            index = HelpersRepository.findIndexById(parent.children, item.id);
            parent.children = _.without(parent.children, parent.children[index]);
        }
        else {
            index = HelpersRepository.findIndexById(that.items, item.id);
            that.items = _.without(that.items, that.items[index]);
        }
    },

    /**
     * This works. It seems kind of complicated, but I tried other ways
     * and they both had problems.
     *
     * Finding parent by path broke down when zoomed on an item, because path was not the full path.
     *
     * Finding parent with _.flatten broke down when not zoomed on an item
     * and items were expanded several levels.
     *
     * @param array
     * @param item
     * @param oldParentId
     * @returns {*}
     */
    findParent: function (array, item, oldParentId, resetParent) {
        var parent;
        var that = this;
        var parentId = item.parent_id;

        if (resetParent) {
            //It's the first time the method is being called, rather than it being called from within itself.
            //So make sure the parent isn't set from a previous time the method was called.
            that.parent = null;
        }

        if (oldParentId) {
            parentId = oldParentId;
        }

        if (!parentId || oldParentId === null) {
            return false;
        }
        $(array).each(function () {
            if (this.id === parentId) {
                parent = this;
                that.parent = this;
                return false;
            }
            if (this.children) {
                return that.findParent(this.children, item, oldParentId);
            }
        });

        console.log(that.parent);
        return that.parent;
    }
};
var Vue = require('vue');
require('sweetalert2');

module.exports = {
    /**
     * storeProperty is the store property to set once the items are loaded.
     * loadedProperty is the store property to set once the items are loaded, to indicate that the items are loaded.
     * todo: allow for sending data: add {params:data} as second argument
     */
    get: function (options) {
        store.showLoading();
        Vue.http.get(options.url).then(function (response) {
            if (options.callback) {
                options.callback(response.data);
            }

            if (options.storeProperty) {
                if (options.updatingArray) {
                    //Update the array the item is in
                    store.update(response.data, options.storeProperty);
                }
                else {
                    store.set(response.data, options.storeProperty);
                }
            }

            if (options.loadedProperty) {
                store.set(true, options.loadedProperty);
            }

            store.hideLoading();
        }, function (response) {
            helpers.handleResponseError(response.data, response.status);
        });
    },

    /**
     * options:
     * array: store array to add to
     */
    post: function (options) {
        store.showLoading();
        Vue.http.post(options.url, options.data).then(function (response) {
            if (options.callback) {
                options.callback(response.data);
            }

            store.hideLoading();

            if (options.message) {
                $.event.trigger('provide-feedback', [options.message, 'success']);
            }

            if (options.array) {
                store.add(response.data, options.array);
            }

            if (options.clearFields) {
                options.clearFields();
            }

            if (options.redirectTo) {
                router.go(options.redirectTo);
            }
        }, function (response) {
            helpers.handleResponseError(response.data, response.status);
        });
    },

    /**
     *
     */
    put: function (options) {
        store.showLoading();
        Vue.http.put(options.url, options.data).then(function (response) {
            if (options.callback) {
                options.callback(response.data);
            }

            store.hideLoading();

            if (options.message) {
                $.event.trigger('provide-feedback', [options.message, 'success']);
            }

            if (options.property) {
                store.update(response.data, options.property);
            }

            if (options.redirectTo) {
                router.go(options.redirectTo);
            }

        }, function (response) {
            helpers.handleResponseError(response.data, response.status);
        });
    },

    /**
     *
     */
    delete: function (options) {
        swal({
            title: 'Are you sure?',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-default',
            buttonsStyling: false,
            reverseButtons: true,
            showCloseButton: true
        }).then(function() {
            store.showLoading();
            Vue.http.delete(options.url).then(function (response) {
                if (options.callback) {
                    options.callback(response);
                }

                store.hideLoading();

                if (options.message) {
                    $.event.trigger('provide-feedback', [options.message, 'success']);
                }

                if (options.array) {
                    store.delete(options.itemToDelete, options.array);
                }

                if (options.redirectTo) {
                    router.go(options.redirectTo);
                }
            }, function (response) {
                helpers.handleResponseError(response.data, response.status);
            });
        }, function(dismiss) {

        });
    },
};
/**
 * This file currently isn't being used.
 * @type {{findSiblingsWithItem: SortableRepository.findSiblingsWithItem, findParentById: SortableRepository.findParentById, setNewIndex: SortableRepository.setNewIndex, setNewParent: SortableRepository.setNewParent, setNewTarget: SortableRepository.setNewTarget, setMouseDown: SortableRepository.setMouseDown}}
 */
module.exports = {

    /**
     * Return an array of the item's siblings including the item itself
     * @param $array
     * @param $item
     * @returns {Array}
     */
    findSiblingsWithItem: function ($array, $item) {
        var $parent = findParent($array, $item);
        var $siblings = [];

        if ($parent) {
            $siblings = $parent.children;
        }
        else {
            $($array).each(function () {
                $siblings.push(this);
            });
        }

        return $siblings;
    },

    /**
     * This breaks down when not zoomed on an item
     * and items are expanded several levels. findParent works better.
     * @param $item
     * @param $items
     * @returns {*}
     */
    findParentById: function ($item, $items) {
        if (!$item.parent_id) {
            return false;
        }

        var $parent = _.findWhere(_.flatten($items), {id: $item.parent_id});
        return $parent;
    },

    /**
     * For when item is hovered, setting the index to that of the hovered item
     * @param $index
     */
    setNewIndex: function ($index) {
        newIndex = $index;
    },

    /**
     * For when item is hovered, setting the newParent to that of the hovered item
     * @param $parent
     */
    setNewParent: function ($parent) {
        newParent = $parent;
    },

    /**
     * For when item is hovered, setting the newTarget to that of the hovered item,
     * so I can show the guide at the right place
     * @param $parent
     */
    setNewTarget: function ($target) {
        newTarget = $target;
    },

    setMouseDown: function ($boolean) {
        mouseDown = $boolean;
    },

};

var Vue = require('vue');
var VueResource = require('vue-resource');
Vue.use(VueResource);
var helpers = require('./Helpers');
var object = require('lodash/object');
require('sugar');
Date.setLocale('en-AU');

module.exports = {

    state: {
        me: {gravatar: ''},
        loading: false,
        categories: [],
        categoriesLoaded: false
    },

    /**
     *
     */
    showLoading: function () {
        this.state.loading = true;
    },

    /**
     *
     */
    hideLoading: function () {
        this.state.loading = false;
    },

    /**
     *
     */
    getCategories: function () {
        helpers.get({
            url: '/api/categories',
            storeProperty: 'categories',
            loadedProperty: 'categoriesLoaded'
        });
    },

    /**
     * Add an item to an array
     * @param item
     * @param path
     */
    add: function (item, path) {
        object.get(this.state, path).push(item);
    },

    /**
     * Update an item that is in an array
     * @param item
     * @param path
     */
    update: function (item, path) {
        var index = helpers.findIndexById(object.get(this.state, path), item.id);

        object.get(this.state, path).$set(index, item);
    },

    /**
     * Set a property (can be nested)
     * @param data
     * @param path
     */
    set: function (data, path) {
        object.set(this.state, path, data);
    },

    /**
     * Toggle a property (can be nested)
     * @param path
     */
    toggle: function (path) {
        object.set(this.state, path, !object.get(this.state, path));
    },

    /**
     * Delete an item from an array
     * To delete a nested property of store.state, for example a class in store.state.classes.data:
     * store.delete(itemToDelete, 'student.classes.data');
     * @param itemToDelete
     * @param path
     */
    delete: function (itemToDelete, path) {
        object.set(this.state, path, helpers.deleteById(object.get(this.state, path), itemToDelete.id));
    }
};
module.exports = {

    /**
     *
     * @param alarm
     * @returns {*}
     */
    getTimeFromNow: function (alarm) {
        if (alarm.indexOf('mins') != -1) {
            var index = alarm.indexOf('mins');
            var minutesFromNow = alarm.substring(0, index).trim();
            alarm = moment().add(minutesFromNow, 'minutes').format('YYYY-MM-DD HH:mm:ss');
        }
        else if (alarm.indexOf('hours') != -1) {
            var index = alarm.indexOf('hours');
            var hoursFromNow = alarm.substring(0, index).trim();
            alarm = moment().add(hoursFromNow, 'hours').format('YYYY-MM-DD HH:mm:ss');
        }
        else if (alarm.indexOf('secs') != -1) {
            var index = alarm.indexOf('secs');
            var secondsFromNow = alarm.substring(0, index).trim();
            alarm = moment().add(secondsFromNow, 'seconds').format('YYYY-MM-DD HH:mm:ss');
        }
        else if (alarm.indexOf('weeks') != -1) {
            var index = alarm.indexOf('weeks');
            var weeksFromNow = alarm.substring(0, index).trim();
            alarm = moment().add(weeksFromNow, 'weeks').format('YYYY-MM-DD HH:mm:ss');
        }
        else if (alarm.indexOf('months') != -1) {
            var index = alarm.indexOf('months');
            var monthsFromNow = alarm.substring(0, index).trim();
            alarm = moment().add(monthsFromNow, 'months').format('YYYY-MM-DD HH:mm:ss');
        }

        return alarm;
    },

    /**
     *
     * @param alarm
     * @param match = a day of the week
     * @returns {string}
     */
    getTimeFromDayAndTime: function (alarm, match) {
        var date = moment().day(match.num).format('YYYY-MM-DD');
        var time = alarm.replace(match.day, '').trim();
        time = Date.parse(time).toString('HH:mm:ss');
        return date + ' ' + time;
    },

    days: [
        {
            day: 'sun',
            num: 7
        },
        {
            day: 'mon',
            num: 8
        },
        {
            day: 'tue',
            num: 9
        },
        {
            day: 'wed',
            num: 10
        },
        {
            day: 'thu',
            num: 11
        },
        {
            day: 'fri',
            num: 12
        },
        {
            day: 'sat',
            num: 13
        }
    ]
};
require('sugar');

module.exports = {
    ready: function () {
        //Set Sugar to use Australian date formatting
        Date.setLocale('en-AU');
        store.getCategories();
    }
};
// require('./bootstrap');

var Vue = require('vue');
global.$ = require('jquery');
global.jQuery = require('jquery');
global._ = require('underscore');
global.store = require('./repositories/Store');
var VueResource = require('vue-resource');
Vue.use(VueResource);
require('./config.js');
global.helpers = require('./repositories/Helpers');
global.filters = require('./repositories/Filters');
// Date.setLocale('en-AU');

//Shared components
Vue.component('navbar', require('./components/shared/NavbarComponent.vue'));
Vue.component('feedback', require('@jennyswift/feedback'));
Vue.component('loading', require('./components/shared/LoadingComponent.vue'));
// Vue.component('popup', require('./components/shared/PopupComponent.vue'));
// Vue.component('autocomplete', require('@jennyswift/vue-autocomplete'));

// Vue.component('date-navigation', require('./components/shared/DateNavigationComponent.vue'));
// Vue.component('buttons', require('./components/shared/ButtonsComponent.vue'));
// Vue.component('input-group', require('./components/shared/InputGroupComponent.vue'));
// Vue.component('checkbox-group', require('./components/shared/CheckboxGroupComponent.vue'));

//Components
Vue.component('item-popup', require('./components/ItemPopupComponent.vue'));
Vue.component('alarms', require('./components/AlarmsComponent.vue'));
Vue.component('urgent-items', require('./components/UrgentItemsComponent.vue'));
Vue.component('favourite-items', require('./components/FavouriteItemsComponent.vue'));
Vue.component('filter', require('./components/FilterComponent.vue'));
Vue.component('new-item', require('./components/NewItemComponent.vue'));
Vue.component('item', require('./components/ItemComponent.vue'));

//Filters

//Transitions
// Vue.transition('fade', require('./transitions/FadeTransition'));

require('./routes');


//# sourceMappingURL=all.js.map
