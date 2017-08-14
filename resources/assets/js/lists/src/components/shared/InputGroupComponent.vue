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
            :unfiltered-options="options"
            :function-on-enter="enter"
            :selected.sync="mutableModel"
            :option-partial="optionPartial"
        >
        </autocomplete>

    </div>




</template>

<script>
//    import Vue from 'vue'

    export default {
        data: function () {
            return {
                mutableModel: this.model
            };
        },
        computed: {
//            mutableModel: function () {
//              return this.model;
//            }
        },
        methods: {
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
                this.$emit('update:model', this.mutableModel);
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
            topBorder: {}

        }
    }
</script>