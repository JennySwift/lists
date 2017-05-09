<template>
    <div class="datepicker">

        <div class="datepicker-input-label-container">

            <div class="datepicker-label-container">
                <!--<div class="tooltip_templates">-->
                    <!--<div id="{{inputId}}-datepicker-help">-->
                        <!--<date-picker-help></date-picker-help>-->
                    <!--</div>-->
                <!--</div>-->

                <label :for="inputId" class="">{{label}}:</label>
                <!--<span class="tooltipster fa fa-question-circle" data-tooltip-content="{{dataTooltipContent}}"></span>-->

            </div>

            <div>
                <div class="input-group">
                    <input
                        v-on:keyup="keyup"
                        v-on:keyup.13="functionOnEnter()"
                        v-model="mutableChosenDate"
                        type="text"
                        :id="inputId"
                        :name="inputId"
                        :placeholder="inputPlaceholder"
                        class="form-control datepicker-input"
                    >
                    <span class="input-group-btn">
                        <button v-on:click="toggleCalendar()" class="btn btn-default">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </span>
                </div>
            </div>

            <div v-if="showDateFeedback" class="date-feedback-for-user">{{ mutableChosenDate | dateAndTimeFilter }}</div>
        </div>




        <div v-show="showCalendar" transition="fade" class="datepicker-calendar-container">
            <div class="datepicker-heading">
                <button v-on:click="previousMonth()" class="btn btn-default fa fa-chevron-left"></button>
                <div class="month-and-year-heading class=date">{{ monthName }} {{ year }}</div>
                <button v-on:click="nextMonth()" class="btn btn-default fa fa-chevron-right"></button>
            </div>

            <table class="table table-bordered datepicker-calendar">
                <tr>
                    <th>Su</th>
                    <th>Mo</th>
                    <th>Tu</th>
                    <th>We</th>
                    <th>Th</th>
                    <th>Fr</th>
                    <th>Sa</th>
                </tr>
                <tr v-for="week in weeks">
                    <td v-on:click="chooseDate(week.Sunday)" class="date">{{ week.Sunday }}</td>
                    <td v-on:click="chooseDate(week.Monday)" class="date">{{ week.Monday }}</td>
                    <td v-on:click="chooseDate(week.Tuesday)" class="date">{{ week.Tuesday }}</td>
                    <td v-on:click="chooseDate(week.Wednesday)" class="date">{{ week.Wednesday }}</td>
                    <td v-on:click="chooseDate(week.Thursday)" class="date">{{ week.Thursday }}</td>
                    <td v-on:click="chooseDate(week.Friday)" class="date">{{ week.Friday }}</td>
                    <td v-on:click="chooseDate(week.Saturday)" class="date">{{ week.Saturday }}</td>
                </tr>
            </table>
        </div>

    </div>
</template>

<script>
    var moment = require('moment');
    var $ = require('jquery');

    module.exports = {
        template: '#date-picker-template',
        data: function () {
            return {
                year: this.setYear(),
                monthNumber: this.setMonthNumber(),
                showCalendar: false,
                mutableChosenDate: this.chosenDate
            };
        },
        components: {},
        computed: {
            monthName: function () {
                return moment().month(this.monthNumber - 1).format('MMMM');
            },
            weeks: function () {
                var daysInMonth = moment(this.year + '-' + this.monthNumber, 'YYYY-M-D').daysInMonth();
                var weeks = [
                    []
                ];
                var weekIndex = 0;

                for (var i = 0; i < daysInMonth; i++) {
                    var date = i + 1;
                    var dayName = moment(this.year + '-' + this.monthNumber + '-' + date, 'YYYY-M-D').format('dddd');
                    var dayNumber = this.getDayNumber(dayName);

                    if (dayNumber === 1 && date !== 1) {
                        //Start a new week
                        weeks.push([]);
                        weekIndex++;
                    }

                    weeks[weekIndex][dayName] = date;
                }

                return weeks;
            },
            dataTooltipContent: function () {
                return '#' + this.inputId + '-datepicker-help';
            }
        },
        filters: {
            /**
             *
             * @returns {*}
             */
            dateAndTimeFilter: function (date) {
                return helpers.convertFromDateTime(helpers.convertToDateTime(date));
            }
        },
        methods: {

            keyup: function () {
                this.$emit('update:chosenDate', this.mutableChosenDate);
            },

            /**
             *
             */
            toggleCalendar: function () {
                if (!this.showCalendar) {
                    this.showCalendar = true;
//                    $(this.$el).find('datepicker-calendar-container').animate({height: 300}, 500);
                }
                else {
                    this.hideCalendar();
                }
            },

            /**
             *
             */
            hideCalendar: function () {
//                $(this.$el).animate({height: 34}, 500);
                this.showCalendar = false;
            },

            /**
             *
             * @param dayOfMonth
             */
            chooseDate: function (dayOfMonth) {
                this.mutableChosenDate = moment(this.year + '-' + this.monthNumber + '-' + dayOfMonth, 'YYYY-M-D').format('ddd DD MMM YYYY');
                this.hideCalendar();
                // this.$dispatch('date-chosen', this.mutableChosenDate, this.property);
                this.$emit('date-chosen', [this.mutableChosenDate]);
            },

            /**
             *
             */
            nextMonth: function () {
                if (this.monthNumber === 12) {
                    this.monthNumber = 1;
                }
                else {
                    this.monthNumber++;
                }
                if (this.monthNumber === 1) {
                    this.year++;
                }
            },

            /**
             *
             */
            previousMonth: function () {
                if (this.monthNumber === 1) {
                    this.monthNumber = 12;
                }
                else {
                    this.monthNumber--;
                }
                if (this.monthNumber === 12) {
                    this.year--;
                }
            },

            /**
             *
             * @returns {*}
             */
            setMonthNumber: function () {
                return moment().format('M');
            },

            /**
             *
             * @returns {string}
             */
            setYear: function () {
                return moment().format('YYYY');
            },

            /**
             *
             * @param day
             * @returns {number}
             */
            getDayNumber: function (day) {
                switch(day) {
                    case 'Sunday':
                        return 1;
                        break;
                    case 'Monday':
                        return 2;
                        break;
                    case 'Tuesday':
                        return 3;
                        break;
                    case 'Wednesday':
                        return 4;
                        break;
                    case 'Thursday':
                        return 5;
                        break;
                    case 'Friday':
                        return 6;
                        break;
                    case 'Saturday':
                        return 7;
                        break;
                }
            }
        },
        props: {
            functionOnEnter: {
                required: false,
                type: Function,
                default: function () {
                    return true;
                }
            },
            chosenDate: {},
            inputId: {},
            inputPlaceholder: {},
            label: {},
            showDateFeedback: {},
        },
        mounted: function () {

        }
    };

</script>

<style lang="scss" rel="stylesheet/scss">
    $base1:  lighten(#3CD3AD, 30%);
    $base2: #97D7D6;
    $base3: #B0E4DC;
    $base4: $base2;
    $breakpoint2: '768px';


    $zIndex1: 9;
    $zIndex2: 99;

    @mixin inputGroupLabel {
        background: lighten($base1, 10%);
        color: darken($base2, 10%);
        min-width: 160px;
        text-align: right;
        @media all and (max-width: $breakpoint2) {
            min-width: 105px;
        }
    }

    .datepicker {
        position: relative;
        .btn {
            height: 34px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-top: 0;
            left: 1px;
        }
        .datepicker-input-label-container {
            display: flex;
            .datepicker-label-container {
                @include inputGroupLabel;
                display: flex;
                justify-content: flex-end;
                padding: 6px 12px;
                align-items: center;
                label {
                    font-weight: normal;
                    margin-bottom: 0;
                }
                .fa {
                    margin-left: 3px;
                }
                /*border-top: 1px solid #ccc;*/
                border-left: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
            }
        }
        table {
            margin-bottom: 0;
            td {
                padding: 8px;
            }
        }
        .date-feedback-for-user {
            position: absolute;
            right: 48px;
            top: 8px;
            z-index: $zIndex1;
            @media (max-width: 800px) {
                display: none;
            }
        }
        .input-group {
            width: 100%;
        }
        .datepicker-heading {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            .month-and-year-heading {
                font-size: 20px;
                margin: 0 10px;
                width: 150px;
                text-align: center;
            }
        }
        .datepicker-input {
            //margin-bottom: 20px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top: 0;
            border-right: 0;
        }
        .datepicker-calendar-container {
            position: absolute;
            width: 100%;
            top: 35px;
            padding-top: 8px;
            background: white;
            z-index: $zIndex2;
        }
        .datepicker-calendar {
            //position: absolute;
        }
        td, th {
            text-align: right;
        }
        .date {
            cursor: pointer;
            &:hover {
                background: $base1;
                color: white;
            }
        }
    }
</style>