<template>
    <f7-popup class="selector-popup">
        <f7-page>
            <f7-navbar>
                <f7-nav-title>Popup</f7-nav-title>
                <f7-nav-right>
                    <f7-link @click="closePopup">Close</f7-link>
                </f7-nav-right>
            </f7-navbar>

            <f7-list no-hairlines-md contacts-list>
                <f7-list-item v-for="option in shared.categories" :key="option.id" v-on:click="selectOption(option)">
                    {{option.name}}
                </f7-list-item>
            </f7-list>
        </f7-page>
    </f7-popup>

</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state
            }
        },
        methods: {
            closePopup: function () {
                store.closePopup('.selector-popup');
            },
            selectOption: function (option) {
                this.$emit('update:model', option);
                this.closePopup();
            }
        },
        props: [
            'model'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>