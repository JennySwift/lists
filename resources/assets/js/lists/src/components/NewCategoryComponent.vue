<template>
    <div id="new-category">
        <div class="input-group-container">
            <input
                v-model="newCategory.name"
                v-on:keyup.13="insertCategory"
                type="text"
                placeholder="Enter a new category"
                class="line"/>

        </div>

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