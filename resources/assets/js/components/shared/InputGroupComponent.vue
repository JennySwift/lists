<template>
    <div class="input-group">
        <!--Label-->
        <label :for="id" class="input-group-addon">
            {{label}}
            <!--Tooltip-->
            <span v-if="tooltipId" class="tooltipster fa fa-question-circle" data-tooltipster='{"side":"bottom"}' data-tooltip-content="#{{tooltipId}}"></span>
        </label>

        <!--Text input-->
        <input
            v-if="!options && !url"
            v-model="model"
            v-on:keyup.13="enter()"
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

        },
        props: [
            'label',
            'model',
            'id',
            'url',
            'options',
            'optionsProp',
            //Text to add after each option
            'optionsAdditionalText',
            'tooltipId',
            //Method to run on enter
            'enter',
            'optionPartial'
        ]
    };
</script>