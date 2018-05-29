<template>
    <f7-subnavbar id="breadcrumb">
        <f7-link href="/items/">Home</f7-link>
        <!--<i v-if="shared.breadcrumb.length > 0" class="fa fa-angle-right"></i>-->

        <f7-link v-for="(item, key, index) in shared.breadcrumb" :key="item.id" :href="'/items/' + item.id">{{ item.title }}</f7-link>
        <!--<i v-if="index !== shared.breadcrumb.length - 1" class="fa fa-angle-right"></i>-->
    </f7-subnavbar>

</template>

<script>
    import store from '../repositories/Store'
    export default {
        data: function () {
            return {
                shared: store.state
            }
        }
    }
</script>

<style lang="scss" type="text/scss">
    #breadcrumb {
        .subnavbar-inner {
            overflow: scroll;
            a {
                flex-grow: 1;
            }
            /*.segmented {*/
                /*width: auto;*/
                /*.button, .segmented button {*/
                    /*width: auto;*/
                /*}*/
            /*}*/
        }
    }
</style>