<template>
    <div id="categories" class="padded-container">

        <new-popup id="category-popup">
            <div slot="content">
                <input
                    type="text"
                    v-model="selectedCategory.name"
                    v-on:keyup.13 = "updateCategory"
                    placeholder="Name"
                    class="line"
                />
            </div>
            <buttons slot="buttons"
                :save="updateCategory"
                :destroy="deleteCategory"
            >
            </buttons>
        </new-popup>

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
                    confirmMessage: 'Are you sure? All items with this category will be deleted can NOT be restored from the trash.',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        helpers.hidePopup('category-popup');
                    }.bind(this)
                });
            },

            /**
             *
             */
            showCategoryPopup: function (category) {
                this.selectedCategory = helpers.clone(category);
                helpers.showPopup('category-popup');
            }
        }
    }
</script>