<template>
    <f7-navbar>
        <f7-nav-left>
            <f7-link icon-if-ios="f7:menu" icon-if-md="material:menu" panel-open></f7-link>
        </f7-nav-left>
        <f7-nav-title>{{title}}</f7-nav-title>
        <f7-nav-right>
            <f7-link panel-open="right"><f7-icon f7="search"></f7-icon></f7-link>
            <!--<f7-link icon-if-ios="f7:menu" icon-if-md="material:menu" :popover-open="'#' + popoverId + '-menu'"></f7-link>-->


            <!--<f7-popover :id="popoverId + '-menu'">-->
            <!--<f7-list contacts-list>-->
            <!--<f7-list-group>-->
            <!--<f7-list-item class="panel-close" link="/sessions" title="Sessions" popover-close></f7-list-item>-->
            <!--<f7-list-item class="panel-close" link="/workouts" title="Workouts" popover-close></f7-list-item>-->
            <!--<f7-list-item class="panel-close" link="/exercises/" title="Exercises" popover-close></f7-list-item>-->
            <!--<slot></slot>-->
            <!--</f7-list-group>-->
            <!--</f7-list>-->
            <!--</f7-popover>-->
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
            'title'
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