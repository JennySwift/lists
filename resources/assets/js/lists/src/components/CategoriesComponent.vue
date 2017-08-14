<template>
    <div id="categories">

        <modal name="category-popup" @before-open="beforeOpen">
            <div class="input-group-container">
                <input-group
                    label="Name:"
                    :model.sync="selectedCategory.name"
                    :enter="updateCategory"
                    id="selected-category-name"
                >
                </input-group>

                <buttons
                    :save="updateCategory"
                    :destroy="deleteCategory"
                >
                </buttons>
            </div>
        </modal>

        <h1>Categories</h1>

        <new-category></new-category>

        <ul class="list-group">
            <li v-for="category in shared.categories" class="list-group-item">
                <div
                    v-on:click="showCategoryPopup(category)"
                    class="pointer"
                >
                    {{ category.name }}
                </div>
            </li>
        </ul>

    </div>
</template>

<script>
    import store from '../repositories/Store'
    import helpers from '../repositories/Helpers'
    export default {
        template: '#categories-template',
        data: function () {
            return {
                shared: store.state,
                selectedCategory: {}
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
                    property: 'categories',
                    message: 'Category updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        helpers.hidePopup('category-popup');
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
                        helpers.hidePopup('category-popup');
                    }.bind(this)
                });
            },

            showModal () {
                this.$modal.show('hello-world', { foo: 'bar' });
            },

            /**
             *
             */
            showCategoryPopup: function (category) {
//                $.event.trigger('show-category-popup', [this.category]);
                this.selectedCategory = helpers.clone(category);
                this.$modal.show('category-popup', { foo: 'bar' });
            }
        }
    }
</script>