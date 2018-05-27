<template>
    <f7-page>
        <navbar title="Category"></navbar>

        <f7-list no-hairlines-md contacts-list>
            <f7-list-item>
                <f7-label>Name</f7-label>
                <f7-input type="text" :value="shared.category.name" @input="shared.category.name = $event.target.value" clear-button=""></f7-input>
            </f7-list-item>
        </f7-list>

        <f7-block>
            <buttons
                :save="updateCategory"
                :destroy="deleteCategory"
                :redirect-to="redirectTo"
            >
            </buttons>
        </f7-block>
    </f7-page>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
                category: {},
                redirectTo: '/categories',
                baseUrl: '/api/categories'
            }
        },
        methods: {

            /**
             *
             */
            getCategory: function () {
                var id = helpers.getIdFromRouteParams(this);

                helpers.get({
                    url: this.baseUrl + '/' + id,
                    storeProperty: 'category',
                    callback: function () {
                        // store.set(helpers.clone(this.shared.category), 'categoryClone');
                    }.bind(this)
                });
            },

            /**
             *
             */
            updateCategory: function () {
                var data = {
                    name: this.shared.category.name
                };

                helpers.put({
                    url: this.baseUrl + '/' + this.shared.category.id,
                    data: data,
                    property: 'categories',
                    message: 'Category updated',
                    redirectTo: this.redirectTo,
                });
            },

            /**
             *
             */
            deleteCategory: function () {
                helpers.delete({
                    url: this.baseUrl + this.shared.category.id,
                    array: 'categories',
                    itemToDelete: this.shared.category,
                    message: 'Category deleted',
                    confirmTitle: 'Are you sure?',
                    confirmText: 'All items with this category will be deleted, and can NOT be restored from the trash!',
                    redirectTo: this.redirectTo
                });
            },
        },
        mounted: function () {
            this.getCategory();
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>