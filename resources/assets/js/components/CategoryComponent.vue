<template>
    <div
        v-on:click="showCategoryPopup()"
        class="pointer"
    >
        {{ category.name }}
    </div>
</template>

<script>
    module.exports = {
        template: '#category-template',
        data: function () {
            return {

            };
        },
        components: {},
        methods: {

            /**
             *
             */
            showCategoryPopup: function () {
                $.event.trigger('show-category-popup', [this.category]);
            }
        },
        props: [
            'category'
        ],
        mounted: function () {

        }
    };
</script>
