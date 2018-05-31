<template>
    <f7-popup class="selector-popup" :id="id">
        <f7-page>
            <f7-navbar>
                <f7-nav-title>Popup</f7-nav-title>
                <f7-nav-right>
                    <f7-link @click="closePopup">Close</f7-link>
                </f7-nav-right>
            </f7-navbar>

            <f7-list no-hairlines-md contacts-list>
                <f7-list-item v-if="any" v-on:click="selectOption(false)">Any</f7-list-item>
                <f7-list-item v-for="option in options" :key="option.id" v-on:click="selectOption(option)">
                    {{option[displayProp]}}
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
                if (this.model) {
                    this.$emit('update:model', option);
                }
                if (this.path) {
                    store.set(option, this.path);
                }
                if (this.onSelect) {
                    this.onSelect(option);
                }

                this.closePopup();
            }
        },
        props: {
            displayProp: {
                default: 'name'
            },
            options: {},
            model: {},
            path: {},
            id: {},
            //Add 'Any' option before the other options
            any: {},
            onSelect: {}
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>