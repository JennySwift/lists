<template>
    <popup
        :show-popup.sync="showPopup"
        id="category-popup"
        :redirect-to="redirectTo"
        :update="updateCategory"
        :destroy="deleteCategory"
    >
        <div slot="content">
            <div class="input-group-container">
                <input-group
                    label="Name:"
                    :model.sync="selectedCategory.name"
                    :enter="updateCategory"
                    id="selected-category-name"
                >
                </input-group>
            </div>

            <buttons
                :save="updateCategory"
                :destroy="deleteCategory"
                :redirect-to="redirectTo"
            >
            </buttons>

        </div>
    </popup>

</template>

<script>
    module.exports = {
        template: '#category-popup-template',
        data: function () {
            return {
                selectedCategory: {},
                showPopup: false,
                shared: store.state,
                redirectTo: '/categories'
            };
        },
        components: {},
        methods: {

            /**
            *
            */
            updateCategory: function () {
                var data = {
                    name: this.selectedCategory.name
                };

                helpers.put({
                    url: '/api/categories/' + this.selectedCategory.id,
                    data: data,
                    property: 'categorys',
                    message: 'Category updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {

                    }.bind(this)
                });
            },

            /**
            *
            */
            deleteCategory: function () {
                helpers.delete({
                    url: '/api/categories/' + this.selectedCategory.id,
                    array: 'categories',
                    itemToDelete: this.selectedCategory,
                    message: 'Category deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.showPopup = false;
                    }.bind(this)
                });
            },

            /**
             *
             */
            closePopup: function ($event) {
                helpers.closePopup($event, this);
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
        ready: function () {
            this.listen();
        }
    };
</script>
