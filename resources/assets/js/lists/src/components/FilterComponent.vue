<template>

    <div class="popup filter-popup">
        <div class="view filter-view">
            <f7-page>
                <f7-navbar>
                    <f7-nav-title>Filter</f7-nav-title>
                    <f7-nav-right>
                        <f7-link popup-close>Close</f7-link>
                    </f7-nav-right>
                </f7-navbar>

                <f7-list no-hairlines-md contacts-list>

                    <f7-list-item v-on:click="setSelectorOptions(shared.favouriteItems)" title="Favourite Items" link popup-open="#filter-favourites-selector"></f7-list-item>
                    <selector displayProp="title" id="filter-favourites-selector" :on-select="goToSelectedItem"></selector>

                    <f7-list-item>
                        <f7-label>Minimum Priority</f7-label>
                        <f7-input type="text" :value="shared.filters.minimumPriority" @input:clear="shared.filters.minimumPriority = $event.target.value" @input="shared.filters.minimumPriority = $event.target.value" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item>
                        <f7-label>Priority</f7-label>
                        <f7-input type="text" :value="shared.filters.priority" @input:clear="shared.filters.priority = $event.target.value" @input="shared.filters.priority = $event.target.value" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item>
                        <f7-label>Title</f7-label>
                        <f7-input type="text" :value="shared.filters.title" @input:clear="shared.filters.title = $event.target.value" @input="shared.filters.title = $event.target.value" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item>
                        <f7-label>Note</f7-label>
                        <f7-input type="text" :value="shared.filters.body" @input:clear="shared.filters.body = $event.target.value" @input="shared.filters.body = $event.target.value" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item v-on:click="setSelectorOptions(shared.categories)" title="Category" link popup-open="#filter-category-selector">
                        <div slot="after">{{shared.filters.category.name}}</div>
                    </f7-list-item>
                    <selector :any="true" display-prop="name" path="filters.category" id="filter-category-selector"></selector>

                    <f7-list-item title="Show Trashed">
                        <f7-toggle @change="shared.filters.showTrashed = !shared.filters.showTrashed" :checked="shared.filters.showTrashed"></f7-toggle>
                    </f7-list-item>

                    <f7-list-item title="Search all by title" v-on:click="setSelectorOptions([])" link popup-open="#filter-all-by-title-selector">
                    </f7-list-item>
                    <selector display-prop="title" url="/api/items" :on-select="goToSelectedItem" field-to-filter-by="title" id="filter-all-by-title-selector"></selector>

                    <f7-list-item title="Search all by note" v-on:click="setSelectorOptions([])" link popup-open="#filter-all-by-note-selector">
                    </f7-list-item>
                    <selector display-prop="title" url="/api/items" :on-select="goToSelectedItem" field-to-filter-by="body" id="filter-all-by-note-selector"></selector>

                    <f7-list-item title="Show Future Items">
                        <f7-toggle @change="shared.filters.includeFutureItems = !shared.filters.includeFutureItems" :checked="shared.filters.includeFutureItems"></f7-toggle>
                    </f7-list-item>
                    <f7-list-item v-if="shared.filters.includeFutureItems" title="Not Before" link popup-open="#filter-not-before-date-picker">
                        <div slot="after">{{shared.filters.notBeforeDate}}</div>
                    </f7-list-item>
                    <date-picker id="filter-not-before-date-picker" :initial-date-value.sync="shared.filters.notBeforeDate"></date-picker>
                </f7-list>

            </f7-page>
        </div>

    </div>

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
                shared: store.state
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

            setSelectorOptions: function (options) {
                store.set(options, 'selectorOptions.data');
            },

            goToSelectedItem: function (item) {
                store.closePopup('.filter-popup');
                helpers.goToRoute("/items/" + item.id);
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

            dateChosen: function (date, id) {
                if (id === 'filter-not-before-date-picker') {
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