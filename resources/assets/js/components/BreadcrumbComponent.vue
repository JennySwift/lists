<template>
    <div id="breadcrumb">
        <div>
            <router-link to="/items/">Home</router-link>
            <i v-if="shared.breadcrumb.length > 0" class="fa fa-angle-right"></i>
        </div>
        <div v-for="item in shared.breadcrumb">
            <a v-link="{ path: '/items/:' + item.id }">
                {{ item.title }}
            </a>
            <i v-if="$index !== shared.breadcrumb.length - 1" class="fa fa-angle-right"></i>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data: function () {
            return {
                shared: store.state
            }
        }
    };
</script>