<template>
    <div
        v-show="mutableShowPopup"
        transition="popup-outer"
        v-on:click="closePopup($event)"
        class="popup-outer animate"
    >

        <div
            v-show="mutableShowPopup"
            transition="popup-inner"
            :id="id"
            class="popup-inner animate"
        >

            <div class="content">
                <slot name="content"></slot>
            </div>

            <div class="buttons">
                <slot name="buttons">

                </slot>
            </div>

        </div>
    </div>
</template>

<script>
    import helpers from '../../repositories/Helpers'
    export default {
        data: function () {
            return {

            };
        },
        computed: {
            mutableShowPopup: function () {
              return this.showPopup;
            }
        },
        components: {},
        methods: {
            /**
             *
             */
            closePopup: function ($event) {
                helpers.closePopup($event, this, this.redirectTo);
            }
        },
        props: [
            'showPopup',
            'id',
            'redirectTo'
        ]
    }

</script>

