<template>
    <div class="data-table data-table-init card">
        <slot></slot>
        <div class="card-content">
            <table>
                <slot name="table-content"></slot>
            </table>

        </div>

    </div>
</template>

<script>
    export default {
        methods: {

        },
    }
</script>

<style lang="scss" type="text/scss">

</style>