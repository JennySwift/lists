var expect = require('chai').expect;
var assert = require('chai').assert;
var Vue = require('vue');
global.ItemsRepository = require('../resources/assets/js/repositories/ItemsRepository');

describe('deleting item', function () {
    var vm;

    beforeEach(function () {
        vm = new Vue(require('../resources/assets/js/components/ItemPopupComponent.vue'));
    });

    it('can delete an item', function () {
        store.state.items = [
            {
                title: '1',
                id: 1,
            },
            {
                title: '2',
                id: 2
            }
        ];
        var item = {
            title: '1',
            id: 1,
        };

        ItemsRepository.deleteJsItem(item);

        var expected = [
            {
                title: '2',
                id: 2
            }
        ];

        assert.deepEqual(expected, store.state.items);
    });
});