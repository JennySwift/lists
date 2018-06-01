<template>
    <f7-popup class="date-picker-popup" :id="id">
        <f7-page>
            <f7-navbar>
                <f7-nav-title>Date Picker</f7-nav-title>
                <f7-nav-right>
                    <f7-link @click="closePopup">Close</f7-link>
                </f7-nav-right>
            </f7-navbar>

            <!--<f7-block>-->
                <!--<f7-list no-hairlines-md contacts-list>-->
                    <!--<f7-list-item>-->
                        <!--<f7-label>Type or click a date</f7-label>-->
                        <!--<f7-input type="text" :value="dateInput" @input:clear="dateInput = $event.target.value"  clear-button=""></f7-input>-->
                    <!--</f7-list-item>-->
                <!--</f7-list>-->

            <!--</f7-block>-->

            <f7-block>
                <f7-button @click="chooseToday">Today</f7-button>
                <f7-button @click="chooseNone">None</f7-button>
            </f7-block>

            <data-table>
                <div class="card-header">
                    <div class="data-table-title">{{ monthName }} {{ year }}</div>
                    <div class="data-table-actions">
                        <f7-segmented>
                            <f7-button v-on:click="previousMonth()"><f7-icon f7="chevron_left"></f7-icon></f7-button>
                            <f7-button v-on:click="nextMonth()"><f7-icon f7="chevron_right"></f7-icon></f7-button>
                        </f7-segmented>
                    </div>
                </div>
                <template slot="table-content">
                    <tr>
                        <th>Su</th>
                        <th>Mo</th>
                        <th>Tu</th>
                        <th>We</th>
                        <th>Th</th>
                        <th>Fr</th>
                        <th>Sa</th>
                    </tr>
                    <tbody>
                        <tr v-for="week in weeks">
                            <td v-on:click="chooseDateWithDatePicker(week.Sunday)" class="date">{{ week.Sunday }}</td>
                            <td v-on:click="chooseDateWithDatePicker(week.Monday)" class="date">{{ week.Monday }}</td>
                            <td v-on:click="chooseDateWithDatePicker(week.Tuesday)" class="date">{{ week.Tuesday }}</td>
                            <td v-on:click="chooseDateWithDatePicker(week.Wednesday)" class="date">{{ week.Wednesday }}</td>
                            <td v-on:click="chooseDateWithDatePicker(week.Thursday)" class="date">{{ week.Thursday }}</td>
                            <td v-on:click="chooseDateWithDatePicker(week.Friday)" class="date">{{ week.Friday }}</td>
                            <td v-on:click="chooseDateWithDatePicker(week.Saturday)" class="date">{{ week.Saturday }}</td>
                        </tr>
                        </tbody>
                </template>
            </data-table>


        </f7-page>
    </f7-popup>

</template>

<script>
    var moment = require('moment');

    export default {
        template: '#date-picker-template',
        data: function () {
            return {
                year: this.setYear(),
                monthNumber: this.setMonthNumber(),
                showCalendar: false,
                dateInput: this.initialDateValue,
                datePickerChosenDate: ''
            };
        },
        components: {},
        watch: {
            initialDateValue () {
                this.dateInput = this.initialDateValue;
            }
        },
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

            closePopup: function () {
                store.closePopup('.date-picker-popup');
            },

            keyup: function () {
//                this.$emit('update:chosenDate', this.datePickerChosenDate);
                this.syncDateFromInput();
            },

            /**
             *
             */
            toggleCalendar: function () {
                if (!this.showCalendar) {
                    this.showCalendar = true;
                }
                else {
                    this.hideCalendar();
                }
            },

            /**
             *
             */
            hideCalendar: function () {
                this.showCalendar = false;
            },

            /**
             *
             * @param dayOfMonth
             */
            chooseDateWithDatePicker: function (dayOfMonth) {
                this.datePickerChosenDate = moment(this.year + '-' + this.monthNumber + '-' + dayOfMonth, 'YYYY-M-D').format('ddd DD MMM YYYY');
                this.dateChosen();
            },

            chooseToday: function () {
                this.datePickerChosenDate = moment().format('ddd DD MMM YYYY');
                this.dateChosen();
            },

            chooseNone: function () {
                this.datePickerChosenDate = false;
                this.dateChosen();
            },

            dateChosen: function () {
                this.syncDateFromDatePicker();
                this.closePopup();
            },

            syncDateFromDatePicker () {
//                this.dateInput = this.datePickerChosenDate;
                this.$emit('update:initialDateValue', this.dateInput);
                this.$emit('date-chosen', this.datePickerChosenDate, this.id);
                this.$bus.$emit('date-chosen', this.datePickerChosenDate, this.id);
            },

            syncDateFromInput () {
                this.$bus.$emit('date-chosen', this.dateInput, this.inputId);
            },

            /**
             *
             */
            nextMonth: function () {
                if (parseInt(this.monthNumber) === 12) {
                    this.monthNumber = 1;
                }
                else {
                    this.monthNumber++;
                }
                if (parseInt(this.monthNumber) === 1) {
                    this.year++;
                }
            },

            /**
             *
             */
            previousMonth: function () {
                if (parseInt(this.monthNumber) === 1) {
                    this.monthNumber = 12;
                }
                else {
                    this.monthNumber--;
                }
                if (parseInt(this.monthNumber) === 12) {
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
//            functionOnEnter: {
//                required: false,
//                type: Function,
//                default: function () {
//                    return true;
//                }
//            },
            initialDateValue: {},
//            inputId: {},
//            inputPlaceholder: {},
//            label: {},
//            showDateFeedback: {},
            id: {}
        },
        mounted: function () {

        }
    }

</script>

<style lang="scss" type="text/scss">
    //@import '../../../../sass/shared/index';
    .date-picker-popup {
        .segmented .button {
            text-overflow: initial;
        }
    }
</style>