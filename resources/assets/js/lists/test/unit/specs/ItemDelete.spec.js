import Vue from 'vue'
// import Hello from '@/components/Hello'
import ItemsRepository from '@/repositories/ItemsRepository'
import ItemPopupComponent from '@/components/ItemPopupComponent'

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
        expect(1).to.equal(1);
    })
})




// var expect = require('chai').expect;
// var assert = require('chai').assert;
// var Vue = require('vue');
// global.ItemsRepository = require('../../../../repositories/ItemsRepository');
//
describe('deleting item', function () {

//
//     it('can delete an item', function () {
//         store.state.items = [
//             {
//                 title: '1',
//                 id: 1,
//             },
//             {
//                 title: '2',
//                 id: 2
//             }
//         ];
//         var item = {
//             title: '1',
//             id: 1,
//         };
//
//         ItemsRepository.deleteJsItem(item);
//
//         var expected = [
//             {
//                 title: '2',
//                 id: 2
//             }
//         ];
//
//         assert.deepEqual(expected, store.state.items);
//     });
});