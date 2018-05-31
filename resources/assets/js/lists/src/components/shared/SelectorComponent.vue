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
                <f7-list-item>
                    Hello
                </f7-list-item>
            </f7-list>
        </f7-page>
    </f7-popup>

</template>

<script>
    export default {
        methods: {
            closePopup: function () {
                store.closePopup('.selector-popup');
            }
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>