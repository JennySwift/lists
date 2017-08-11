var expect = require('chai').expect;
var assert = require('chai').assert;
var Vue = require('vue');
global.ItemsRepository = require('../../resources/assets/js/repositories/ItemsRepository');

describe('path to item', function () {
    var vm;

    beforeEach(function () {
        vm = new Vue(require('../../resources/assets/js/components/ItemPopupComponent.vue'));

        store.state.items = [
            {
                title: '1',
                id: 1,
                children: [
                    {
                        title: '1.1',
                        id: 4,
                        parent_id: 1,
                        children: [
                            {
                                title: '1.1.1',
                                id: 7,
                                parent_id: 1
                            },
                            {
                                title: '1.1.2',
                                id: 8,
                                parent_id: 4,
                                children: [
                                    {
                                        title: '1.1.2.1',
                                        id: 9,
                                        parent_id: 8
                                    },
                                    {
                                        title: '1.1.2.2',
                                        id: 10,
                                        parent_id: 8
                                    },
                                    {
                                        title: '1.1.2.3',
                                        id: 11,
                                        parent_id: 8
                                    },
                                    {
                                        title: '1.1.2.4',
                                        id: 12,
                                        parent_id: 8,
                                        children: [
                                            {
                                                title: '1.1.2.4.1',
                                                id: 13,
                                                parent_id: 12
                                            },
                                        ]
                                    },
                                ]
                            },
                        ]
                    },
                    {
                        title: '1.2',
                        id: 5,
                        parent_id: 1
                    },
                    {
                        title: '1.3',
                        id: 6,
                        parent_id: 1
                    }
                ]
            }
        ];
    });

    it('can get an array of ancestor ids for an item', function () {
        var item = {
            title: '1.1.2.4.1',
            id: 13,
            parent_id: 12
        };

        var result = ItemsRepository.getAncestorIds(item, []);

        var expectedResult = [1,4,8,12];

        console.log('result: ' + result);

        assert.deepEqual(expectedResult, result);
    });

    it('can get an array of indexes for the path to an item, based on an array of ancestor ids', function () {
        var ancestorIds = [1,4,8,12];

        var result = ItemsRepository.getPath(null, ancestorIds, [], 0);

        var expectedResult = [0,0,1,3];

        console.log('result: ' + result);

        assert.deepEqual(expectedResult, result);
    });
});