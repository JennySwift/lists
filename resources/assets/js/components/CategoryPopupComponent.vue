<template>
    <div
        v-show="showPopup"
        v-on:click="closePopup($event)"
        class="popup-outer"
    >

        <div id="category-popup" class="popup-inner">

            <div class="form-group">
                <label for="selected-category-name">Name</label>
                <input
                    v-model="selectedCategory.name"
                    type="text"
                    id="selected-category-name"
                    name="selected-category-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <div class="buttons">
                <button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
                <button v-on:click="deleteCategory()" class="btn btn-danger">Delete</button>
                <button v-on:click="updateCategory()" class="btn btn-success">Save</button>
            </div>

        </div>
    </div>
</template>

<script>
    module.exports = {
        template: '#category-popup-template',
        data: function () {
            return {
                selectedCategory: {},
                showPopup: false,
                shared: store.state
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
