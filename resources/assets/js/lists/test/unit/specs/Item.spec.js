var expect = require('chai').expect;
var assert = require('chai').assert;
import Vue from 'vue'
import ItemsRepository from '@/repositories/ItemsRepository'
import ItemPopupComponent from '@/components/ItemPopupComponent.vue'
import store from '@/repositories/Store'


describe('move item', function () {
    var vm;

    describe('string path', function () {
        it('can make a string of the path to the item', function () {
            var path = [0,0,1,3];
            var result = ItemsRepository.createPathAsString(path);
            var expected = 'items[0].children[0].children[1].children[3].children';
            assert.equal(expected, result);
        });
    });

    describe('nested deeper', function () {
        beforeEach(function () {
            vm = new Vue(ItemPopupComponent);
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
                },
                {
                    title: '2',
                    id: 2,
                    children: [
                        {
                            title: '2.1',
                            id: 14,
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

        it('can remove an item from an old parent', function () {
            store.state.selectedItemClone = {
                oldParentId: 12,
                title: '1.1.2.4.1',
                id: 13,
                parent_id: 12
            };

            vm.removeFromOldParent({title: '1.1.2.4.1', id: 13, parent_id: 2});

            var expectedOldParent = {
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
                                            // {
                                            //     title: '1.1.2.4.1',
                                            //     id: 13,
                                            //     parent_id: 12
                                            // },
                                        ],
                                        has_children: false
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
            };

            // console.log('\n\n first item: ' + JSON.stringify(store.state.items[0], null, 4) + '\n\n');
            // console.log('\n\n expected: ' + JSON.stringify(expectedOldParent, null, 4) + '\n\n');
            assert.deepEqual(expectedOldParent, store.state.items[0]);
        });


        //     it('can move an item to a new parent', function () {
        //         // console.log('\n\n items before: ' + JSON.stringify(store.state.items, null, 4) + '\n\n');
        //         vm.selectedItem = {
        //             oldParentId: 1,
        //             title: '1.2',
        //             id: 5,
        //             parent_id: 1
        //         };
        //         var itemMoved = {title: '1.2', id: 5, parent_id: 2};
        //         vm.jsMoveToNewParent(itemMoved);
        //
        //         var expectedItems = [
        //             {
        //                 title: '1',
        //                 id: 1,
        //                 children: [
        //                     {
        //                         title: '1.1',
        //                         id: 4,
        //                         parent_id: 1
        //                     },
        //                     //Item 1.2 was moved so no longer here
        //                     // {
        //                     //     title: '1.2',
        //                     //     id: 5,
        //                     //     parent_id: 1
        //                     // },
        //                     {
        //                         title: '1.3',
        //                         id: 6,
        //                         parent_id: 1
        //                     }
        //                 ]
        //             },
        //             {
        //                 title: '2',
        //                 id: 2,
        //                 children: [
        //                     {
        //                         title: '2.1',
        //                         id: 7,
        //                         parent_id: 2
        //                     },
        //                     {
        //                         title: '1.2',
        //                         id: 5,
        //                         parent_id: 2
        //                     },
        //                 ]
        //             },
        //             {
        //                 title: '3',
        //                 id: 3,
        //             },
        //         ];
        //
        //         assert.deepEqual(expectedItems, store.state.items);
        //
        //
        //
        //         // console.log('\n\n expected: ' + JSON.stringify(expectedItems, null, 4) + '\n\n');
        //         // console.log('\n\n items after: ' + JSON.stringify(store.state.items, null, 4) + '\n\n');
        //     });
        // });

    });

    describe('nested only one level', function () {
        beforeEach(function () {
            vm = new Vue(ItemPopupComponent);
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

        it('can remove an item from an old parent', function () {
            store.state.selectedItemClone = {
                oldParentId: 1,
                title: '1.2',
                id: 5,
                parent_id: 1
            };

            vm.removeFromOldParent({title: '1.2', id:5, parent_id: 2});

            var expectedOldParent = {
                title: '1',
                id: 1,
                children: [
                    {
                        title: '1.1',
                        id: 4,
                        parent_id: 1
                    },
                    //Item 1.2 was moved so no longer here
                    // {
                    //     title: '1.2',
                    //     id: 5,
                    //     parent_id: 1
                    // },
                    {
                        title: '1.3',
                        id: 6,
                        parent_id: 1
                    }
                ]
            };

            // console.log('\n\n first item: ' + JSON.stringify(store.state.items[0], null, 4) + '\n\n');
            // console.log('\n\n expected: ' + JSON.stringify(expectedOldParent, null, 4) + '\n\n');
            assert.deepEqual(expectedOldParent, store.state.items[0]);
        });

        it('can remove the last item from an old parent and know that the parent no longer has children', function () {
            store.state.selectedItemClone = {
                oldParentId: 2,
                title: '2.1',
                id: 7,
                parent_id: 2
            };

            vm.removeFromOldParent({title: '2.1', id:7, parent_id: 3});

            var expectedOldParent = {
                title: '2',
                id: 2,
                children: [],
                has_children: false
            };

            assert.deepEqual(expectedOldParent, store.state.items[1]);
        });

        it('can move an item to a new parent and know that the new parent has children', function () {
            vm.selectedItem = {
                oldParentId: 2,
                title: '2.1',
                id: 7,
                parent_id: 2
            };

            vm.jsMoveToNewParent({title: '2.1', id:7, parent_id: 3});

            var expectedNewParent = {
                title: '3',
                id: 3,
                has_children: true
            };

            assert.deepEqual(expectedNewParent, store.state.items[2]);
        });

        it('can move an item to a new parent', function () {
            // console.log('\n\n items before: ' + JSON.stringify(store.state.items, null, 4) + '\n\n');
            store.state.selectedItemClone = {
                oldParentId: 1,
                title: '1.2',
                id: 5,
                parent_id: 1
            };
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
                        //Item 1.2 was moved so no longer here
                        // {
                        //     title: '1.2',
                        //     id: 5,
                        //     parent_id: 1
                        // },
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
                    ],
                    has_children: true
                },
                {
                    title: '3',
                    id: 3,
                },
            ];

            assert.deepEqual(expectedItems, store.state.items);



            // console.log('\n\n expected: ' + JSON.stringify(expectedItems, null, 4) + '\n\n');
            // console.log('\n\n items after: ' + JSON.stringify(store.state.items, null, 4) + '\n\n');
        });
    });


});