<template>
    <div id="categories" class="container">

        <loading></loading>

        <category-popup
            :categories.sync="categories"
        >
        </category-popup>

        <h1>categories</h1>

        <label>Create a new category</label>
        <input
            v-on:keyup.13="insertCategory()"
            type="text"
            placeholder="new category"
            id="new-category"/>

        <button v-on:click="insertCategory()" class="btn btn-success">Create</button>

        <ul>
            <li v-for="category in shared.categories | orderBy 'name'">
                <category
                    :category="category"
                >
                </category>
            </li>
        </ul>

    </div>
</template>

<script>
    module.exports = {
        template: '#categories-template',
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        methods: {

            /**
            *
            */
            insertCategory: function () {
                var data = {
                    name: $("#new-category").val()
                };

                $("#new-category").val("");

                helpers.post({
                    url: '/api/categories',
                    data: data,
                    array: 'categories',
                    message: 'Category created',
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.showPopup = false;
                    }.bind(this)
                });
            },

            /**
             *
             */
            showNewCategoryFields: function () {
                this.addingNewCategory = true;
                this.editingCategory = false;
            }
        }
    };
</script>