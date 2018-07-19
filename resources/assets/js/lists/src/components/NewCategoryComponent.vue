<template>
    <div>
        <f7-list no-hairlines-md contacts-list>
            <f7-list-item>
                <f7-label>New Category Name</f7-label>
                <f7-input type="text" :value="newCategory.name" @input="newCategory.name = $event.target.value" @input:clear="newCategory.name = ''" clear-button=""></f7-input>
            </f7-list-item>
        </f7-list>
        <f7-block>
            <f7-button @click="insertCategory">Add</f7-button>
        </f7-block>
    </div>

</template>

<script>
    import store from '../repositories/Store'
    import helpers from '../repositories/Helpers'
    export default {
        data: function () {
            return {
                shared: store.state,
                newCategory: {name: ''}
            };
        },
        methods: {
            /**
             *
             */
            insertCategory: function () {
                var data = {
                    name: this.newCategory.name
                };

                helpers.post({
                    url: '/api/categories',
                    data: data,
                    array: 'categories',
                    message: 'Category created',
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo,
                    callback: function () {

                    }.bind(this)
                });
            },

            /**
             *
             */
            clearFields: function () {
                this.newCategory.name = '';
            },
        }
    }
</script>