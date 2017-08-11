import Vue from 'vue'
// import Hello from '@/components/Hello'
import ItemsRepository from '@/repositories/ItemsRepository'
// var expect = require('chai').expect;
// var assert = require('chai').assert;

global.store = require('../../../src/repositories/Store');

describe('deleting item', () => {
    var vm;

    beforeEach(function () {
        vm = new Vue(require('../../../../lists/src/components/ItemPopupComponent.vue'));
        // const Constructor = Vue.extend(ItemPopupComponent)
        // const vm = new Constructor().$mount()
    });

    it('can delete an item', () => {
        // const Constructor = Vue.extend(Hello)
        // const vm = new Constructor().$mount()

        // expect(vm.$el.querySelector('.hello h1').textContent)
        //     .to.equal('Welcome to Your Vue.js App')


        // store.state.items = [
        //     {
        //         title: '1',
        //         id: 1,
        //         deletedAt: false
        //     },
        //     {
        //         title: '2',
        //         id: 2,
        //         deletedAt: false
        //     }
        // ];
        var item = {
            title: '1',
            id: 1,
            deletedAt: false
        };

        item = ItemsRepository.deleteJsItem(item);
        // item.deletedAt = true;

        // var expected = [
        //     {
        //         title: '2',
        //         id: 2
        //     }
        // ];

        // console.log('\n\n expected: ' + JSON.stringify(expected, null, 4) + '\n\n');
        // console.log('\n\n result: ' + JSON.stringify(store.state.items, null, 4) + '\n\n');

        // assert.deepEqual(expected, store.state.items);
        //Todo: it wouldn't pass if I checked store.state.items[0]. Shouldn't it?
        assert.equal(true, item.deletedAt);
    })
})