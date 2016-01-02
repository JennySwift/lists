var Categories = Vue.component('categories', {
    template: '#categories-template',
    data: function () {
        return {
            showLoading: false,
            categories: categories
            //addingNewCategories: false,
            //editingCategories: false,
            //selectedCategories: {}
        };
    },
    components: {},
    methods: {

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
                this.categories.push(response);
                this.$broadcast('provide-feedback', 'Category created', 'success');
                this.showLoading = false;
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },
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

    }
});