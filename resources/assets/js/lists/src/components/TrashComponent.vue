<template>
    <div id="trash" class="container">

        <ul>
            <li v-for="item in trashedItems">
                {{item.title}}
                <span>Deleted at {{item.deletedAt}}</span>
            </li>
        </ul>




        <!--<ul id="items">-->

            <!--<item-->
                <!--v-for="item in items"-->
                <!--:item="item"-->
            <!--&gt;-->
            <!--</item>-->

            <!--<div v-if="items.length === 0">No items here</div>-->
        <!--</ul>-->
    </div>
</template>

<script>
    import helpers from '../repositories/Helpers'
    export default {
        data: function () {
            return {
                trashedItems: []
            };
        },
        components: {},
        methods: {

            /**
            *
            */
            getTrashedItems: function () {
                helpers.get({
                    url: '/api/items?trashed=true',
                    callback: function (response) {
                        this.trashedItems = response;
                    }.bind(this)
                });
            }
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            this.getTrashedItems();
        }
    }
</script>