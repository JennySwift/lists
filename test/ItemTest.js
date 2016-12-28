var expect = require('chai').expect;
var assert = require('chai').assert;
var Vue = require('vue');

describe.only('move item', function () {
    var vm;

    beforeEach(function () {
        vm = new Vue(require('../resources/assets/js/components/ItemPopupComponent.vue'));
        store.state.items = [
            {
                title: '1',
                id: 1,
                children: [
                    {
                        title: '1.1',
                        id: 4,
                        parent_id: 1
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
            },
            {
                title: '2',
                id: 2,
                children: [
                    {
                        title: '2.1',
                        id: 7,
                        parent_id: 2
                    }
                ]
            },
            {
                title: '3',
                id: 3,
            },
        ];
    });




    it('can move an item to a new parent', function () {
        // console.log('\n\n items before: ' + JSON.stringify(store.state.items, null, 4) + '\n\n');
        var test = 2;
        assert.equal(2, test);
        var itemMoved = {title: '1.2', id: 5, parent_id: 2};
        vm.jsMoveToNewParent(itemMoved);

        var expectedItems = [
            {
                title: '1',
                id: 1,
                children: [
                    {
                        title: '1.1',
                        id: 4,
                        parent_id: 1
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
            },
            {
                title: '2',
                id: 2,
                children: [
                    {
                        title: '2.1',
                        id: 7,
                        parent_id: 2
                    },
                    {
                        title: '1.2',
                        id: 5,
                        parent_id: 2
                    },
                ]
            },
            {
                title: '3',
                id: 3,
            },
        ];

        assert.deepEqual(expectedItems, store.state.items);



        console.log('\n\n expected: ' + JSON.stringify(expectedItems, null, 4) + '\n\n');
        console.log('\n\n items after: ' + JSON.stringify(store.state.items, null, 4) + '\n\n');
    });
});