<template>
    <f7-popup class="selector-popup" :id="id">
        <f7-page with-subnavbar>
            <f7-navbar>
                <f7-nav-title>Popup</f7-nav-title>
                <f7-nav-right>
                    <f7-link @click="closePopup">Close</f7-link>
                </f7-nav-right>
                <f7-subnavbar v-if="url">
                    <f7-searchbar :custom-search="true" @searchbar:search="onSearch" @input="onInput" :backdrop="false">
                        <div slot="inner-end">
                            <f7-button v-on:click="searchDatabase()">Go</f7-button>
                        </div>
                    </f7-searchbar>
                </f7-subnavbar>
            </f7-navbar>



            <f7-list contacts-list>
                <f7-list-group>
                    <f7-list-item v-if="any" v-on:click="selectOption(false)">Any</f7-list-item>
                    <f7-list-item
                        swipeout
                        v-for="option in shared.selectorOptions.data"
                        :key="option.id"
                        v-bind:title="getTitle(option)"
                        v-on:click="selectOption(option)"
                        class="item"
                    >

                        <div slot="root-end" class="action-btns">
                            <div class="action-btn" v-on:click="openItemPopup(option)"><span>View/Edit</span></div>
                            <div class="action-btn" v-on:click="deleteItem(item)"><span>Delete</span></div>
                        </div>

                        <f7-swipeout-actions left>
                            <f7-swipeout-button close color="blue" overswipe v-on:click="openItemPopup(option)">View/Edit</f7-swipeout-button>
                        </f7-swipeout-actions>

                        <f7-swipeout-actions right>
                            <f7-swipeout-button close color="red" overswipe v-on:click="deleteItem(option)">Delete</f7-swipeout-button>
                        </f7-swipeout-actions>

                    </f7-list-item>
                </f7-list-group>
            </f7-list>

            <f7-toolbar v-if="url && shared.selectorOptions.pagination" class="flex-container">
                <span class="pagination-info">Page {{shared.selectorOptions.pagination.current_page}} of {{shared.selectorOptions.pagination.last_page}}</span>
                <f7-button @click="prevPage()" v-bind:disabled="!shared.selectorOptions.pagination.prev_page_url" class="btn btn-warning">Prev</f7-button>
                <f7-button @click="nextPage()" v-bind:disabled="!shared.selectorOptions.pagination.next_page_url" class="btn btn-warning">Next</f7-button>
            </f7-toolbar>

        </f7-page>
    </f7-popup>

</template>

<script>
    import ItemsRepository from "../../repositories/ItemsRepository";

    export default {
        data: function () {
            return {
                shared: store.state,
                searchTerm: '',
//                mutableOptions: []
            }
        },
        methods: {
            getTitle: function (option) {
                if (this.displayProp) {
                    return option[this.displayProp];
                }
                else if (option.title) {
                    return option.title;
                }
                else if (typeof option === 'string') {
                    return option;
                }
                return "";
            },
            prevPage: function () {
                this.searchDatabase(this.shared.selectorOptions.pagination.current_page-1);
            },

            nextPage: function () {
                this.searchDatabase(this.shared.selectorOptions.pagination.current_page+1);
            },

            openItemPopup: function (item) {
                this.selectOption(item);
                store.openItemPopup(item);
            },

            deleteItem: function (item) {
                ItemsRepository.deleteItem(item);
            },

            searchDatabase: function (pageNumber) {
                var url = this.url + '?' + this.fieldToFilterBy  + '=' + this.searchTerm;
                if (pageNumber) {
                    url+='&page='+ pageNumber;
                }
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
//                this.$emit('selected', option);
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
    @import '../../../../../sass/shared/index';
    .selector-popup {
        @include actionButtons;
        @include item;
    }
</style>