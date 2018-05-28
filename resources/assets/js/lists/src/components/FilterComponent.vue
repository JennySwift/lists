<template>

    <f7-panel right cover>
        <f7-view id="filter-view">
            <f7-page>
                <h5>Find Anywhere</h5>

                <f7-block>
                    <autocomplete
                        v-if="!isTrashPage"
                        input-id="filter-favourites"
                        prop="title"
                        :unfiltered-options="shared.favouriteItems"
                        input-placeholder="Favourite items"
                    >
                    </autocomplete>
                </f7-block>


                <f7-block>
                    <autocomplete
                        v-if="!isTrashPage"
                        input-id="title-search"
                        prop="title"
                        url="/api/items"
                        input-placeholder="Search all by title"
                    >
                    </autocomplete>
                </f7-block>

                <f7-block>
                    <autocomplete
                        v-if="!isTrashPage"
                        input-id="note-search"
                        prop="body"
                        url="/api/items"
                        input-placeholder="Search all by note"
                        field-to-filter-by="body"
                    >
                    </autocomplete>
                </f7-block>

                <h5>Find in Current Position</h5>

                <f7-list no-hairlines-md contacts-list>
                    <f7-list-item>
                        <f7-label>Title</f7-label>
                        <f7-input type="text" :value="shared.filters.title" @input:clear="shared.filters.title = $event.target.value" @input="shared.filters.title = $event.target.value" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item>
                        <f7-label>Note</f7-label>
                        <f7-input type="text" :value="shared.filters.body" @input:clear="shared.filters.body = $event.target.value" @input="shared.filters.body = $event.target.value" clear-button=""></f7-input>
                    </f7-list-item>

                    <!--<f7-list-item v-if="shared.categories.length > 0" smart-select :smart-select-params="{ searchbar: true, closeOnSelect: true, openIn: 'popup' }" title="Category">-->
                        <!--<select name="categories">-->
                            <!--<option v-for="category in shared.categories" :key="category.id" :value="category.id">{{category.name}}</option>-->
                        <!--</select>-->
                    <!--</f7-list-item>-->

                    <li v-if="shared.categories.length > 0">
                        <a @close="smartSelectClose(smartSelect)" class="item-link smart-select smart-select-init" data-open-in="popup" data-close-on-select="true" data-searchbar="true">
                            <select v-model="shared.filters.category.id" name="categories">
                                <option v-for="category in shared.categories" :key="category.id" :value="category.id">{{category.name}}</option>
                            </select>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-title">Category</div>
                                </div>
                            </div>
                        </a>
                    </li>

                    <f7-list-item>
                        <f7-label>Min Priority</f7-label>
                        <f7-input type="text" :value="shared.filters.minimumPriority" @input:clear="shared.filters.minimumPriority = $event.target.value" @input="shared.filters.minimumPriority = $event.target.value" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item>
                        <f7-label>Priority</f7-label>
                        <f7-input type="text" :value="shared.filters.priority" @input:clear="shared.filters.priority = $event.target.value" @input="shared.filters.priority = $event.target.value" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item title="Show Future Items">
                        <f7-toggle @change="shared.filters.includeFutureItems = !shared.filters.includeFutureItems" :checked="shared.filters.includeFutureItems"></f7-toggle>
                    </f7-list-item>

                    <f7-list-item title="Show trashed">
                        <f7-toggle @change="shared.filters.showTrashed = !shared.filters.showTrashed" :checked="shared.filters.showTrashed"></f7-toggle>
                    </f7-list-item>
                </f7-list>

                <date-picker
                    :initial-date-value.sync="shared.filters.notBeforeDate"
                    input-id="filter-not-before-date"
                    label="Not Before"
                    property="notBeforeDate"
                    @date-chosen="dateChosen"
                    input-placeholder="Not before"
                >
                </date-picker>

                <f7-block>
                    <autocomplete
                        input-id="filter-category"
                        prop="name"
                        :selected.sync="shared.filters.category"
                        :unfiltered-options="categoryOptions"
                        input-placeholder="Category"
                    >
                    </autocomplete>
                </f7-block>
            </f7-page>
        </f7-view>
    </f7-panel>

</template>

<script>
    import DateTimeRepository from '../repositories/DateTimeRepository'
    import ItemsRepository from '../repositories/ItemsRepository'
    import store from '../repositories/Store'
    import helpers from '../repositories/Helpers'
    import filters from '../repositories/Filters'

    export default {
        data: function () {
            return {
                shared: store.state,
            };
        },
        computed: {
            categoryOptions: function () {
                var categories = helpers.clone(this.shared.categories);
                categories.unshift({name: 'Any'});

                return categories;
            },
            isTrashPage () {
                return false;
                // return this.$route.path === '/trash';
            }

        },
        components: {},
        filters: {
            /**
             *
             * @param date
             * @returns {*|string}
             */
            userFriendlyDateFilter: function (date) {
                return DateTimeRepository.convertFromDateTime(DateTimeRepository.convertToDateTime(date), 'ddd DD/MM/YY');
            }
        },
        methods: {

            smartSelectClose: function (smartSelect) {
                console.log(smartSelect);
            },

            prevPage: function () {
                store.goToPreviousPage();
            },

            nextPage: function () {
                store.goToNextPage();
            },

            //For when item is chosen from title search autocomplete
//            zoomChosenItem () {
//                store.zoom();
//            },

//            dateChosen: function (args) {
////                this.$emit('update:model', args[0]);
//                store.set(args[0], 'filters.notBeforeDate');
//            },

            /**
             * For when the 'favourite' button in the item popup is toggled,
             * after the item is saved
             */
            //toggleFavourite: function () {
            //    var $itemInFavourites = _.findWhere(favourites, {id: itemPopup.id});
            //    //Remove the item from the favourites if it is no longer a favourite
            //    if ($itemInFavourites && !itemPopup.favourite) {
            //        favourites = _.without(favourites, $itemInFavourites);
            //    }
            //    //Add the item to favourites if it is now a favourite
            //    else if (!$itemInFavourites && itemPopup.favourite) {
            //        //Todo: put the item in the correct place rather than at the end
            //        favourites.push(itemPopup);
            //    }
            //},

            /**
             *
             */
//             filter: function () {
//                 var filter = $("#filter").val();
//
//                 helpers.get({
//                     url: '/api/items?filter=' + filter,
// //                    storeProperty: 'items',
// //                    loadedProperty: 'itemsLoaded',
//                     callback: function (response) {
//                         this.items = response;
//                     }.bind(this)
//                 });
//             },

            optionChosen: function (option, inputId) {
                if (inputId === 'filter-category') {
                    store.set(option, 'filters.category');
                }
                if (inputId === 'title-search' || inputId === 'note-search' || inputId === 'filter-favourites') {
                    helpers.goToRoute("/items/:" + option.id);
                }
            },

            dateChosen: function (date, inputId) {
                if (inputId === 'filter-not-before-date') {
                    store.set(date, 'filters.notBeforeDate');
                }
            },

            setFilterHeight () {
                var appHeight = $('#app').height();
                var navHeight = $('#navbar-1').height();
                var footerHeight = $('footer').height();
//                console.log("height", appHeight, navHeight, footerHeight);

                var filterHeight = appHeight - navHeight - footerHeight;

                //So there's no white space
                filterHeight+= 1;

                $('#search-container').css({height: filterHeight});

                //So the filter isn't too short if there are enough items on the page, meaning the page will be scrolled
                $('#items-page-container .left-side, #trash .left-side').css({'max-height': filterHeight, 'overflow': 'scroll'});
            }
        },
        created: function () {
            this.$bus.$on('autocomplete-option-chosen', this.optionChosen);
            this.$bus.$on('date-chosen', this.dateChosen);
        },
        mounted: function () {
//            this.showFilter = ItemsRepository.shouldFilterBeShownOnPageLoad();
            this.setFilterHeight();
        }
    }
</script>