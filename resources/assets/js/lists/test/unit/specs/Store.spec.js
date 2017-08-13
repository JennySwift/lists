var expect = require('chai').expect;
var assert = require('chai').assert;
import store from '@/repositories/Store'
// import Vue from 'vue'
// global._ = require('underscore');
// globel.$ = require('jquery');

// var VueRouter = require('vue-router');
// Vue.use(VueRouter);
// global.router = new VueRouter({hashbang: false});

describe.only('filters', function () {
    it('can show and hide the loading symbol', function () {
        store.hideLoading();
        assert.isFalse(store.state.loading);
        store.showLoading();
        assert.isTrue(store.state.loading);
        store.hideLoading();
        assert.isFalse(store.state.loading);
    });

    it('can update an item in an array', function () {
        store.state.exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ];

        var exercise = {name: 'squats', id: 2};

        store.update(exercise, 'exercises');

        expect(store.state.exercises).to.eql([
            {name: 'pushup', id: 1},
            {name: 'squats', id: 2},
            {name: 'pullup', id: 3}
        ]);
    });

    it('can add an item to an array', function () {
        store.state.exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2}
        ];

        var exercise = {name: 'pullup', id: 3};

        store.add(exercise, 'exercises');

        expect(store.state.exercises).to.eql([
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ]);
    });

    it('can remove an item from an array', function () {
        store.state.exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ];

        var exercise = {name: 'squat', id: 2};

        store.delete(exercise, 'exercises');

        expect(store.state.exercises).to.eql([
            {name: 'pushup', id: 1},
            {name: 'pullup', id: 3}
        ]);
    });

    it('can set a store property', function () {
        var exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ];

        store.set(exercises, 'exercises');

        expect(store.state.exercises).to.eql(exercises);
    });
});

describe('delete methods', function () {
    it('can remove an item from an array', function () {
        store.state.exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ];

        var exercise = {name: 'squat', id: 2};

        store.delete(exercise, 'exercises');

        expect(store.state.exercises).to.eql([
            {name: 'pushup', id: 1},
            {name: 'pullup', id: 3}
        ]);
    });

    it('can remove an item from a nested array by passing an array', function () {
        store.state.student = {
            subjects: {
                data: [
                    {name: 'History', id: 1},
                    {name: 'Science', id: 2},
                    {name: 'English', id: 3}
                ]
            }
        };

        var subject = {name: 'Science', id: 2};

        store.delete(subject, ['student', 'subjects', 'data']);

        expect(store.state.student.subjects.data).to.eql([
            {name: 'History', id: 1},
            {name: 'English', id: 3}
        ]);
    });

    it('can remove an item from a nested array by passing a string', function () {
        store.state.student = {
            subjects: {
                data: [
                    {name: 'History', id: 1},
                    {name: 'Science', id: 2},
                    {name: 'English', id: 3}
                ]
            }
        };

        var subject = {name: 'Science', id: 2};

        store.delete(subject, 'student.subjects.data');

        expect(store.state.student.subjects.data).to.eql([
            {name: 'History', id: 1},
            {name: 'English', id: 3}
        ]);
    });
});

describe('add methods', function () {
    it('can add an item to an array', function () {
        store.state.exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2}
        ];

        var exercise = {name: 'pullup', id: 3};

        store.add(exercise, 'exercises');

        expect(store.state.exercises).to.eql([
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ]);
    });

    it('can add an item to a nested array by passing a string', function () {
        store.state.student = {
            subjects: {
                data: [
                    {name: 'History', id: 1},
                    {name: 'Science', id: 2},
                ]
            }
        };

        var subject = {name: 'English', id: 3};

        store.add(subject, 'student.subjects.data');

        expect(store.state.student.subjects.data).to.eql([
            {name: 'History', id: 1},
            {name: 'Science', id: 2},
            {name: 'English', id: 3}
        ]);
    });

    it('can add an item to a nested array by passing an array', function () {
        store.state.student = {
            subjects: {
                data: [
                    {name: 'History', id: 1},
                    {name: 'Science', id: 2},
                ]
            }
        };

        var subject = {name: 'English', id: 3};

        store.add(subject, ['student', 'subjects', 'data']);

        expect(store.state.student.subjects.data).to.eql([
            {name: 'History', id: 1},
            {name: 'Science', id: 2},
            {name: 'English', id: 3}
        ]);
    });
});

describe('update methods', function () {
    it('can update an item in an array', function () {
        store.state.exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ];

        var exercise = {name: 'squats', id: 2};

        store.update(exercise, 'exercises');

        expect(store.state.exercises).to.eql([
            {name: 'pushup', id: 1},
            {name: 'squats', id: 2},
            {name: 'pullup', id: 3}
        ]);
    });

    it('can update an item from a nested array by passing a string', function () {
        store.state.student = {
            subjects: {
                data: [
                    {name: 'History', id: 1},
                    {name: 'Science', id: 2},
                    {name: 'English', id: 3}
                ]
            }
        };

        var subject = {name: 'Blah', id: 2};

        store.update(subject, 'student.subjects.data');

        expect(store.state.student.subjects.data).to.eql([
            {name: 'History', id: 1},
            {name: 'Blah', id: 2},
            {name: 'English', id: 3}
        ]);
    });

    it('can update an item from a nested array by passing an array', function () {
        store.state.student = {
            subjects: {
                data: [
                    {name: 'History', id: 1},
                    {name: 'Science', id: 2},
                    {name: 'English', id: 3}
                ]
            }
        };

        var subject = {name: 'Blah', id: 2};

        store.update(subject, ['student', 'subjects', 'data']);

        expect(store.state.student.subjects.data).to.eql([
            {name: 'History', id: 1},
            {name: 'Blah', id: 2},
            {name: 'English', id: 3}
        ]);
    });
});

describe('property setting', function () {
    it('can set a store property', function () {
        var exercises = [
            {name: 'pushup', id: 1},
            {name: 'squat', id: 2},
            {name: 'pullup', id: 3}
        ];

        store.set(exercises, 'exercises');

        expect(store.state.exercises).to.eql(exercises);
    });

    it('can set a nested store property', function () {
        store.state.schoolYears = [
            {name: 'year one', sortId: 1, id: 1},
            {name: 'year two', sortId: 2, id: 2},
            {name: 'year three', sortId: 3, id: 3}
        ];

        var schoolYear = {name: 'year one', sortId: 3, id: 1};

        store.set(schoolYear.sortId, 'schoolYears[0].sortId');

        expect(store.state.schoolYears[0]).to.eql(schoolYear);
    });

    it('can set a nested store property and use a variable in the path', function () {
        store.state.schoolYears = [
            {name: 'year one', sortId: 1, id: 1},
            {name: 'year two', sortId: 2, id: 2},
            {name: 'year three', sortId: 3, id: 3}
        ];

        var schoolYear = {name: 'year one', sortId: 3, id: 1};
        var index = 0;

        store.set(schoolYear.sortId, ['schoolYears', index, 'sortId']);

        expect(store.state.schoolYears[index]).to.eql(schoolYear);
    });
});