Vue.component('loading', {
    data: function () {
        return {
            showLoading: false
        };
    },
    template: "#loading-template",
    props: [

    ],
    methods: {
        listen: function () {
            var that = this;
            $(document).on('show-loading', function (event, message, type) {
                that.showLoading = true;
            });
            $(document).on('hide-loading', function (event, message, type) {
                that.showLoading = false;
            });
        }
    },
    ready: function () {
        this.listen();
    }
});