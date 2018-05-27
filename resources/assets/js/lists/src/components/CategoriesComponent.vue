<template>
        <f7-page>
            <navbar title="Items"></navbar>

            <f7-list contacts-list>
                <f7-list-group>
                    <f7-list-item
                        v-for="category in shared.categories"
                        v:on:mouseup="setCategory(category)"
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
            };
        },
        components: {},
        methods: {
            setCategory: function (category) {
                store.set(category, 'category');
            }
        }
    }
</script>