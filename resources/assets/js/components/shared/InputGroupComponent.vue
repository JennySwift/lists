<template>
    <div class="input-group" v-bind:class="{'top-border': topBorder}">
        <!--Label-->
        <label :for="id" class="input-group-addon">
            <!--Asterix if required-->
            <span v-if="required" class="fa fa-asterisk"></span>

            {{label}}

            <!--Tooltip-->
            <span v-if="tooltipId" class="tooltipster fa fa-question-circle" data-tooltipster='{"side":"bottom"}' data-tooltip-content="#{{tooltipId}}"></span>
        </label>

        <!--Text input-->
        <input
            v-if="!options && !url"
            v-model="model"
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
            :selected.sync="model"
            :option-partial="optionPartial"
        >
        </autocomplete>

    </div>




</template>

<script>

    module.exports = {
        methods: {
            /**
             * So it doesn't error if a method isn't given to be run when the input is focused
             */
            onFocus: function () {
                if (this.focus) {
                    this.focus();
                }
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
            //Method to run on enter
            enter: {},
            //Method to run on focus
            focus: {

            },
            optionPartial: {},
            required: {},
            topBorder: {}

        }
//        props: [
//            'label',
//            'model',
//            'id',
//            'url',
//            'options',
//            'optionsProp',
//            //Text to add after each option
//            'optionsAdditionalText',
//            'tooltipId',
//            //Method to run on enter
//            'enter',
//            //Method to run on focus
//            'focus',
//            'optionPartial',
//            'required',
//            'topBorder'
//        ]
    };
</script>