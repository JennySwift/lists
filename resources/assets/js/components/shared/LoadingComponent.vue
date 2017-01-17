<template>
    <div v-show="loading" id="loading">
        <i class="fa fa-spinner fa-pulse"></i>
    </div>
</template>

<script>
    module.exports = {
        data: function () {
            return {
                shared: store.state
            };
        },
        template: "#loading-template",
        computed: {
            loading: function () {
                return this.shared.loading;
            }
        }
    };
</script>

