<template>
    <div id="new-category">
        <div class="input-group-container">
            <input-group
                label="New Category:"
                :model.sync="newCategory.name"
                :enter="insertCategory"
                id="new-category-name"
            >
            </input-group>
        </div>

        <button v-on:click="insertCategory()" class="btn btn-success">Create</button>
    </div>
</template>

<script>
    module.exports = {
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