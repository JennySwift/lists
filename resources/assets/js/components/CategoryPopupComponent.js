var CategoryPopup = Vue.component('category-popup', {
    template: '#category-popup-template',
    data: function () {
        return {
            selectedCategory: {},
            showPopup: false
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        updateCategory: function () {
            $.event.trigger('show-loading');

            var data = {
                name: this.selectedCategory.name
            };

            this.$http.put('/api/categories/' + this.selectedCategory.id, data, function (response) {
                var index = _.indexOf(this.categories, _.findWhere(this.categories, {id: this.selectedCategory.id}));
                this.categories[index].name = response.name;
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Category updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        deleteCategory: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/categories/' + this.selectedCategory.id, function (response) {
                    this.categories = _.without(this.categories, this.selectedCategory);
                    //var index = _.indexOf(this.categorys, _.findWhere(this.categorys, {id: this.category.id}));
                    //this.categorys = _.without(this.categorys, this.categorys[index]);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Category deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
        *
        */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-category-popup', function (event, category) {
                that.selectedCategory = category;
                that.showPopup = true;
            });
        }
    },
    props: [
        'categories'
    ],
    ready: function () {
        this.listen();
    }
});
