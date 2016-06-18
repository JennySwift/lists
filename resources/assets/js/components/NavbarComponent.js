var Navbar = Vue.component('navbar', {
    template: '#navbar-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {

        /**
         *
         */
        toggleFilter: function () {
            $.event.trigger('toggle-filter');
        },

        /**
         *
         */
        toggleFavouriteItems: function () {
            $.event.trigger('toggle-favourite-items');
        }
    },
    props: [
        'showFilter'
    ],
    ready: function () {

    }
});
