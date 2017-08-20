<template>

    <transition>
        <div v-show="shared.showFilter" id="search-container">

            <autocomplete
                input-id="title-search"
                prop="title"
                url="/api/items"
                input-placeholder="Search all titles"
            >
            </autocomplete>

            <autocomplete
                input-id="note-search"
                prop="body"
                url="/api/items"
                input-placeholder="Search all notes"
                field-to-filter-by="body"
            >
            </autocomplete>

            <div>
                <!--<label for="">Title: </label>-->
                <input type="text" class="line" v-model="shared.filters.title" placeholder="Title"/>
            </div>

            <div>
                <!--<label for="">Body: </label>-->
                <input type="text" class="line" v-model="shared.filters.body" placeholder="Note"/>
            </div>

            <div>
                <input type="text" class="line" v-model="shared.filters.minimumPriority" placeholder="Min Priority"/>
            </div>

            <div>
                <input type="text" class="line" v-model="shared.filters.priority" placeholder="Priority"/>
            </div>

            <date-picker
                :initial-date-value.sync="shared.filters.notBeforeDate"
                input-id="filter-not-before-date"
                label="Not Before"
                property="notBeforeDate"
                @date-chosen="dateChosen"
                input-placeholder="Not before"
            >
            </date-picker>

            <!--<input-group-->
                <!--label="Category:"-->
                <!--:model.sync="shared.filters.category"-->
                <!--id="filter-category"-->
                <!--:options="categoryOptions"-->
                <!--options-prop="name"-->
            <!--&gt;-->
            <!--</input-group>-->

            <autocomplete
                input-id="filter-category"
                prop="name"
                :selected.sync="shared.filters.category"
                :unfiltered-options="categoryOptions"
            >
            </autocomplete>

            <div class="checkbox-container">
                <label for="filter-not-before">Hide items not before future time:</label>
                <input
                v-model="shared.filters.notBefore"
                id="filter-not-before"
                type="checkbox"
            >
            </div>

            <div class="checkbox-container">
                <label for="filter-not-before">Show trashed items:</label>
                <input
                    v-model="shared.filters.showTrashed"
                    id="filter-show-trashed"
                    type="checkbox"
            >
            </div>




            <!--<div class="input-group-container">-->
                <!--<input-group-->
                    <!--label="Title:"-->
                    <!--:model.sync="shared.filters.title"-->
                    <!--id="title-filter"-->
                <!--&gt;-->
                <!--</input-group>-->

                <!--<input-group-->
                    <!--label="Note:"-->
                    <!--:model.sync="shared.filters.body"-->
                    <!--id="body-filter"-->
                <!--&gt;-->
                <!--</input-group>-->

                <!--<input-group-->
                    <!--label="Min Priority:"-->
                    <!--:model.sync="shared.filters.minimumPriority"-->
                    <!--id="min-priority-filter"-->
                <!--&gt;-->
                <!--</input-group>-->

                <!--<input-group-->
                    <!--label="Priority:"-->
                    <!--:model.sync="shared.filters.priority"-->
                    <!--id="priority-filter"-->
                <!--&gt;-->
                <!--</input-group>-->

                <!--&lt;!&ndash;<input-group&ndash;&gt;-->
                <!--&lt;!&ndash;label="Urgency (in):"&ndash;&gt;-->
                <!--&lt;!&ndash;:model.sync="shared.filters.urgency"&ndash;&gt;-->
                <!--&lt;!&ndash;id="urgency-filter"&ndash;&gt;-->
                <!--&lt;!&ndash;&gt;&ndash;&gt;-->
                <!--&lt;!&ndash;</input-group>&ndash;&gt;-->

                <!--&lt;!&ndash;<input-group&ndash;&gt;-->
                <!--&lt;!&ndash;label="Urgency (out >=):"&ndash;&gt;-->
                <!--&lt;!&ndash;:model.sync="shared.filters.urgencyOut"&ndash;&gt;-->
                <!--&lt;!&ndash;id="urgency-out-filter"&ndash;&gt;-->
                <!--&lt;!&ndash;&gt;&ndash;&gt;-->
                <!--&lt;!&ndash;</input-group>&ndash;&gt;-->

                <!--<date-picker-->
                    <!--:initial-date-value.sync="shared.filters.notBeforeDate"-->
                    <!--input-id="filter-not-before-date"-->
                    <!--label="Not Before"-->
                    <!--property="notBeforeDate"-->
                    <!--@date-chosen="dateChosen"-->
                <!--&gt;-->
                <!--</date-picker>-->

                <!--<input-group-->
                    <!--label="Category:"-->
                    <!--:model.sync="shared.filters.category"-->
                    <!--id="filter-category"-->
                    <!--:options="categoryOptions"-->
                    <!--options-prop="name"-->
                <!--&gt;-->
                <!--</input-group>-->

                <!--<div class="checkbox-container">-->
                    <!--<label for="filter-not-before">Hide items not before future time:</label>-->
                    <!--<input-->
                        <!--v-model="shared.filters.notBefore"-->
                        <!--id="filter-not-before"-->
                        <!--type="checkbox"-->
                    <!--&gt;-->
                <!--</div>-->

                <!--<div class="checkbox-container">-->
                    <!--<label for="filter-not-before">Show trashed items:</label>-->
                    <!--<input-->
                        <!--v-model="shared.filters.showTrashed"-->
                        <!--id="filter-show-trashed"-->
                        <!--type="checkbox"-->
                    <!--&gt;-->
                <!--</div>-->
            <!--</div>-->

        </div>
    </transition>


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
            filter: function () {
                var filter = $("#filter").val();

                helpers.get({
                    url: '/api/items?filter=' + filter,
//                    storeProperty: 'items',
//                    loadedProperty: 'itemsLoaded',
                    callback: function (response) {
                        this.items = response;
                    }.bind(this)
                });
            },

            optionChosen: function (option, inputId) {
                if (inputId === 'filter-category') {
                    store.set(option, 'filters.category');
                }
                if (inputId === 'title-search' || inputId === 'note-search') {
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
                console.log("height", appHeight, navHeight, footerHeight);

                var filterHeight = appHeight - navHeight - footerHeight;

                $('#search-container').css({height: filterHeight});

                //So the filter isn't too short if there are enough items on the page, meaning the page will be scrolled
                $('#items-page-container .left-side').css({'max-height': filterHeight, 'overflow': 'scroll'});
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