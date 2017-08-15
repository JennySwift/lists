<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="input-group" v-bind:class="{'top-border': topBorder}">
        <!--Label-->
        <label :for="id" class="input-group-addon">
            <!--Asterix if required-->
            <span v-if="required" class="fa fa-asterisk"></span>

            {{label}}

            <!--Tooltip-->
            <span
                v-if="tooltipMessage"
                v-tippy
                :title="tooltipMessage"
                class="fa fa-question-circle"
            >
            </span>
        </label>

        <!--Text input-->

        <pre>mutableModel from input group: {{mutableModel}}</pre>
        <pre>mutableModel (from data) from input group: {{$data.mutableModel}}</pre>
        <pre>model from input group: {{model}}</pre>
        <!--<pre>vmodel from input group: {{vModel}}</pre>-->

        <input
            v-if="!options && !url"
            v-model="mutableModel"
            v-on:keyup="sync()"
            v-on:keyup.13="enter()"
            v-on:focus="onFocus()"
            type="text"
            :id="id"
            class="form-control"
        >

        <!--Autocomplete-->
        <autocomplete
            v-if="options || url"
            :url="url"
            :input-id="id"
            :prop="optionsProp"
            :selected.sync="mutableModel"
            :unfiltered-options="options"
            :function-on-enter="enter"
            :option-partial="optionPartial"
        >
        </autocomplete>

    </div>




</template>

<script>
//    import Vue from 'vue'
import store from '../../repositories/Store'
var object = require('lodash/object');

    export default {
        data: function () {
            return {
                mutableModel: '',
                shared: store.state,
//                vModel: this.getVModel()

            };
        },
        watch: {
            model (val) {
                console.log("watch here...");
                console.log("val: ", val);

                this.mutableModel = this.model;
                console.log("model: " + this.model);
                console.log("mutableModel: " + this.mutableModel);
            }
        },
        computed: {
//            mutableModel: function () {
//              return this.model;
//            }
        },
        methods: {
//            getVModel () {
//                console.log('object method returns: ', object.get(this.shared, 'selectedItem.priority'));
//                return this.shared.selectedItem.priority;s
//            },


            /**
             * So it doesn't error if a method isn't given to be run when the input is focused
             */
            onFocus: function () {
                if (this.focus) {
                    this.focus();
                }
            },
//            optionChosen: function (args) {
//                this.$emit('update:model', args[0]);
//            },
            sync: function () {
//                store.set(this.vModel, this.pathToStoreProperty);
                console.log("priority should be set. it is: ", store.state.selectedItem.priority);
//                console.log("mutable before sync: ", this.mutableModel);
                this.$emit('update:model', this.mutableModel);
//                console.log("mutable after sync: ", this.mutableModel);
//                store.set(this.mutableModel, 'selectedItem.priority');
            }
        },
        props: {
            label: {},
            model: {},
            id: {},
            url: {},
            options: {},
            optionsProp: {},
            //Text to add after each option
            optionsAdditionalText: {},
            tooltipId: {},
            tooltipMessage: "",
            //Method to run on enter
            enter: {},
            //Method to run on focus
            focus: {

            },
            optionPartial: {},
            required: {},
            topBorder: {},
            //Store property to keep in sync
            pathToStoreProperty: ''

        },
        created () {

        }
    }
</script>