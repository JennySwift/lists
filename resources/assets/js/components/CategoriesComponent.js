var Categories = Vue.component('categories', {
    template: '#categories-template',
    data: function () {
        return {
            showLoading: false,
            categories: []
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getCategories: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/categories', function (response) {
                this.categories = response;
                $.event.trigger('hide-loading');
            })
            .error(function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
         *
         * @returns {boolean}
         */
        insertCategory: function () {
            $.event.trigger('show-loading');

            var data = {
                name: $("#new-category").val()
            };

            $("#new-category").val("");

            this.$http.post('/api/categories', data, function (response) {
                this.insertCategorySuccess(response);
            })
            .error(function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
         *
         * @param response
         */
        insertCategorySuccess: function (response) {
            this.categories.push(response);
            $.event.trigger('provide-feedback', ['Category created', 'success']);
            //this.$broadcast('provide-feedback', 'Category created', 'success');
            $.event.trigger('hide-loading');
        },

        /**
         *
         */
        showNewCategoryFields: function () {
            this.addingNewCategory = true;
            this.editingCategory = false;
        },
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getCategories();
    }
});