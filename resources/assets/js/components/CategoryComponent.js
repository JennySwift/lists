var Category = Vue.component('category', {
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
    ready: function () {

    }
});
