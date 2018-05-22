<template>
        <f7-page>
            <navbar title="Items"></navbar>

            <!--<new-popup id="category-popup">-->
                <!--<div slot="content">-->
                    <!--<input-->
                        <!--type="text"-->
                        <!--v-model="selectedCategory.name"-->
                        <!--v-on:keyup.13 = "updateCategory"-->
                        <!--placeholder="Name"-->
                        <!--class="line"-->
                    <!--/>-->
                <!--</div>-->
                <!--<buttons slot="buttons"-->
                         <!--:save="updateCategory"-->
                         <!--:destroy="deleteCategory"-->
                <!--&gt;-->
                <!--</buttons>-->
            <!--</new-popup>-->
            <!--<new-category></new-category>-->

            <f7-list contacts-list>
                <f7-list-group>
                    <f7-list-item
                        v-for="category in shared.categories"
                        :key="category.id"
                        :link="'/categories/' + category.id"
                        v-bind:title="category.name"
                    >

                    </f7-list-item>
                </f7-list-group>
            </f7-list>

        </f7-page>
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
                    confirmTitle: 'Are you sure?',
                    confirmText: 'All items with this category will be deleted, and can NOT be restored from the trash!',
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