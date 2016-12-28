var expect = require('chai').expect;
var assert = require('chai').assert;
var Vue = require('vue');
global.store = require('../resources/assets/js/repositories/Store');
global.filters = require('../resources/assets/js/repositories/Filters');
global._ = require('underscore');

describe('filters', function () {
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