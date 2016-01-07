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
            this.showLoading = true;
            this.$http.get('/api/categories', function (response) {
                this.categories = response;
                this.showLoading = false;
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         * @param keycode
         * @returns {boolean}
         */
        insertCategory: function (keycode) {
            if (keycode !== 13) {
                return false;
            }

            this.showLoading = true;

            var data = {
                name: $("#new-category").val()
            };

            $("#new-category").val("");

            this.$http.post('/api/categories', data, function (response) {
                this.insertCategorySuccess(response);
            })
            .error(function (response) {
                this.handleResponseError(response);
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
            this.showLoading = false;
        },

        /**
         *
         */
        showNewCategoryFields: function () {
            this.addingNewCategory = true;
            this.editingCategory = false;
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getCategories();
    }
});