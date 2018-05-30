<template>
    <f7-navbar>
        <f7-nav-left>
            <f7-link icon-if-ios="f7:menu" icon-if-md="material:menu" panel-open></f7-link>
        </f7-nav-left>
        <f7-nav-title>{{title}}</f7-nav-title>
        <f7-nav-right>
            <f7-link class="add" v-if="add" @click="add"><f7-icon f7="add"></f7-icon></f7-link>
            <f7-link v-if="pageHasSearch" @click="openFilterPopup"><f7-icon f7="search"></f7-icon></f7-link>
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
            },
            openFilterPopup: function () {
                store.openPopup('.filter-popup');
            },
        },
        props: [
            'title',
            'pageHasSearch',
            'add'
        ]
    }

</script>

<style lang="scss" type="text/scss">
    #back-navigation-link {
        span {
            padding-top: 1px;
        }
    }
    .add i.icon.f7-icons {
        font-size: 28px;
        width: 50px;
    }
</style>