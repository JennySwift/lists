var assert = require('chai').assert;
import Vue from 'vue'
import DatePickerComponent from '../../../../lists/src/components/shared/DatePickerComponent.vue'
// import helpers from '../../../../lists/src/repositories/Helpers'

const bus = new Vue();
Vue.prototype.$bus = bus;

describe.only('datepicker component', function () {
    var vm;

    beforeEach(function () {
        vm = new Vue(DatePickerComponent);
    });

    describe('date', function () {
        it('can move the calendar to the next month and change the year when the current month is December', function () {
            vm.year = '2017';
            vm.monthNumber = '12';

            assert.equal('2017', vm.year);
            assert.equal(12, vm.monthNumber);

            vm.nextMonth();

            assert.equal('2018', vm.year);
            assert.equal(1, vm.monthNumber);
        });

        it('can move the calendar to the previous month and change the year when the current month is January', function () {
            vm.year = '2018';
            vm.monthNumber = '1';

            assert.equal('2018', vm.year);
            assert.equal(1, vm.monthNumber);

            vm.previousMonth();

            assert.equal('2017', vm.year);
            assert.equal(12, vm.monthNumber);
        });


    });
});