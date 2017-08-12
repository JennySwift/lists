<template>

    <nav v-show="path === '/items' || path === '/'" id="toolbar" class="navbar navbar-default">
        <div class="container-fluid">
            <div id="navbar-2">
                <button class="btn btn-default btn-sm">New Item</button>
            </div>
        </div>
    </nav>

</template>

<script>
    require('bootstrap');

    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        computed: {
            path: function () {
                return this.$route.path;
            }
        },
        components: {},
        methods: {
            
        },
        props: [
            
        ],
        mounted: function () {

        }
    }
</script>