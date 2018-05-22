<template>
    <div id="breadcrumb">
        <div>
            <f7-link href="/items/">Home</f7-link>
            <i v-if="shared.breadcrumb.length > 0" class="fa fa-angle-right"></i>
        </div>
        <div v-for="(item, key, index) in shared.breadcrumb">
            <f7-link
                v-bind:href="'/items/:' + item.id"
            >
                {{ item.title }}
            </f7-link>

            <i v-if="index !== shared.breadcrumb.length - 1" class="fa fa-angle-right"></i>
        </div>
    </div>
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