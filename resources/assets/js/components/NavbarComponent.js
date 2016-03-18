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
        }
    },
    props: [
        'showFilter'
    ],
    ready: function () {

    }
});
