<template>
    <div
        v-show="showPopup"
        transition="popup-outer"
        v-on:click="closePopup($event)"
        class="popup-outer animate"
    >

        <div
            v-show="showPopup"
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
    module.exports = {
        template: '#popup-template',
        data: function () {
            return {
                mutableShowPopup: this.showPopup
                //showPopup: false,
                //selected: {}
            };
        },
        components: {},
        methods: {
            /**
             *
             */
            closePopup: function ($event) {
                helpers.closePopup($event, this, this.redirectTo);
                //router.go('');
            },

            /**
             *
             */
            //listen: function () {
            //    var that = this;
            //    $(document).on('show-popup', function (event, object) {
            //        //that.selected = ;
            //        that.showPopup = true;
            //    });
            //}
        },
        props: [
            'showPopup',
            'id',
            'redirectTo'
        ],
        mounted: function () {
            //this.listen();
            // helpers.scrollbars();
        }
    };

</script>

