<template>
    <f7-popup class="selector-popup" :id="id">
        <f7-page with-subnavbar>
            <f7-navbar>
                <f7-nav-title>Popup</f7-nav-title>
                <f7-nav-right>
                    <f7-link @click="closePopup">Close</f7-link>
                </f7-nav-right>
                <f7-subnavbar v-if="url">
                    <f7-searchbar :custom-search="true" @searchbar:search="onSearch" @input="onInput">
                        <div slot="inner-end">
                            <f7-button v-on:click="searchDatabase">Go</f7-button>
                        </div>
                    </f7-searchbar>
                </f7-subnavbar>
            </f7-navbar>



            <f7-list no-hairlines-md contacts-list>
                <f7-list-item v-if="any" v-on:click="selectOption(false)">Any</f7-list-item>
                <f7-list-item v-for="option in shared.selectorOptions.data" :key="option.id" v-on:click="selectOption(option)">
                    <span v-if="displayProp">{{option[displayProp]}}</span>
                    <span v-if="!displayProp">{{option}}</span>
                </f7-list-item>
            </f7-list>

        </f7-page>
    </f7-popup>

</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
                searchTerm: '',
//                mutableOptions: []
            }
        },
        methods: {
            searchDatabase: function () {
                var url = this.url + '?' + this.fieldToFilterBy  + '=' + this.searchTerm;

                helpers.get({
                    url:  url,
                    storeProperty: 'selectorOptions',
                    callback: function (response) {
//                        this.mutableOptions = response.data;
                    }.bind(this)
                });
            },

            onSearch: function (event) {
                var searchTerm = event.value;
                this.searchTerm = searchTerm;
            },
            onInput: function (event) {
                var key = event.data;
            },
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
//                default: 'name'
            },
//            options: {},
            model: {},
            path: {},
            id: {},
            //Add 'Any' option before the other options
            any: {},
            onSelect: {},
            url: {},
            fieldToFilterBy: {}
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>