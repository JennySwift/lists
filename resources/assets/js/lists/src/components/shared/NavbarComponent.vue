<template>
    <f7-navbar>
        <f7-nav-left>
            <f7-link icon-if-ios="f7:menu" icon-if-md="material:menu" panel-open></f7-link>
        </f7-nav-left>
        <f7-nav-title>{{title}}</f7-nav-title>
        <f7-nav-right v-if="pageHasSearch">
            <f7-link panel-open="right"><f7-icon f7="search"></f7-icon></f7-link>
        </f7-nav-right>
    </f7-navbar>

</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        methods: {
            goToPreviousRoute: function () {
                store.goToPreviousRoute();
            }
        },
        props: [
            'title',
            'pageHasSearch'
        ]
    }

</script>

<style lang="scss" type="text/scss">
    #back-navigation-link {
        span {
            padding-top: 1px;
        }
    }
</style>