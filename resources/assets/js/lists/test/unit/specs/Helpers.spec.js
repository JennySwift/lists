import Vue from 'vue'
var assert = require('chai').assert;
import AutocompleteComponent from '../../../../lists/src/components/shared/AutocompleteComponent.vue'

describe('Autocomplete component', function () {

    describe('keycodes', function () {
        var vm = new Vue(AutocompleteComponent);
        it('can tell if a key is a character', function () {
            assert.isFalse(vm.keyIsCharacter(13));
            assert.isFalse(vm.keyIsCharacter(38));
            assert.isFalse(vm.keyIsCharacter(40));
            assert.isFalse(vm.keyIsCharacter(39));
            assert.isFalse(vm.keyIsCharacter(27));
            assert.isFalse(vm.keyIsCharacter(16));
            assert.isFalse(vm.keyIsCharacter(18));
            assert.isFalse(vm.keyIsCharacter(17));
            assert.isFalse(vm.keyIsCharacter(20));

            assert.isTrue(vm.keyIsCharacter(69));
        });
    });

});