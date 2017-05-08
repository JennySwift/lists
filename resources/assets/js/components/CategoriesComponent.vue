<template>
    <div id="categories">

        <category-popup></category-popup>

        <h1>Categories</h1>

        <new-category></new-category>

        <ul class="list-group">
            <li v-for="category in shared.categories" class="list-group-item">
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
            showNewCategoryFields: function () {
                this.addingNewCategory = true;
                this.editingCategory = false;
            }
        }
    };
</script>