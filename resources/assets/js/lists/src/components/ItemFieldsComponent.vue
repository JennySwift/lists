<template>
    <div>
        <f7-toolbar tabbar>
            <f7-link tab-link-active tab-link=".tab1">Main</f7-link>
            <f7-link tab-link=".tab2">Note</f7-link>
            <f7-link tab-link=".tab3">Scheduling</f7-link>
            <!--<f7-link tab-link=".tab4">Advanced</f7-link>-->
        </f7-toolbar>

        <f7-tabs>
            <f7-tab class="tab1" tab-active>

                <f7-list no-hairlines-md contacts-list>

                    <f7-list-item>
                        <f7-label>Title</f7-label>
                        <f7-input type="textarea" :value="item.title" @input="item.title = $event.target.value" @input:clear="item.title = ''" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item>
                        <f7-label>Priority</f7-label>
                        <f7-input type="text" :value="item.priority" @input="item.priority = $event.target.value" @input:clear="item.priority = ''" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item v-on:click="store.setSelectorOptions(shared.categories)" title="Category" link :popup-open="'#' + action + '-item-category-selector'">
                        <div slot="after">{{item.category.data.name}}</div>
                    </f7-list-item>
                    <selector :model.sync="item.category.data" display-prop="name" :id="action + '-item-category-selector'"></selector>

                    <f7-list-item title="Favourite">
                        <f7-toggle @change="item.favourite = $event.target.checked" :checked="item.favourite"></f7-toggle>
                    </f7-list-item>

                </f7-list>
            </f7-tab>

            <f7-tab class="tab2">
               <f7-list no-hairlines-md contacts-list>
                   <f7-list-item>
                       <f7-label>Note</f7-label>
                       <f7-input type="textarea" :value="item.body" @input="item.body = $event.target.value" @input:clear="item.body = ''" clear-button=""></f7-input>
                   </f7-list-item>
               </f7-list>
            </f7-tab>

            <f7-tab class="tab3">
                <f7-list no-hairlines-md contacts-list>
                    <f7-list-item title="Not Before" link :popup-open="'#' + action + 'item-not-before-date-picker'">
                        <div slot="after">{{item.notBefore}}</div>
                    </f7-list-item>
                    <date-picker v-on:date-chosen="dateChosen" :id="action + 'item-not-before-date-picker'" :initial-date-value.sync="item.notBefore"></date-picker>

                    <f7-list-item v-on:click="store.setSelectorOptions(shared.recurringUnits)" title="Recurring Unit" link :popup-open="'#' + action + '-item-recurring-unit-selector'">
                        <div slot="after">{{item.recurringUnit}}</div>
                    </f7-list-item>
                    <selector :path="storePath + '.recurringUnit'" :model.sync="item.recurringUnit" v-on:selected="onSelected('recurringUnit')" :id="action + '-item-recurring-unit-selector'"></selector>

                    <f7-list-item>
                        <f7-label>Recurring Frequency</f7-label>
                        <f7-input type="text" :value="item.recurringFrequency" @input="item.recurringFrequency = $event.target.value" @input:clear="item.recurringFrequency = ''" clear-button=""></f7-input>
                    </f7-list-item>
                </f7-list>
            </f7-tab>

            <!--<f7-tab class="tab4">-->
                <!--&lt;!&ndash;Parent for inserting item&ndash;&gt;-->
                <!--<input-group-->
                    <!--v-if="action === 'insert'"-->
                    <!--label="Parent:"-->
                    <!--:model.sync="item.parent"-->
                    <!--:enter="enter"-->
                    <!--id="new-item-parent"-->
                    <!--url="/api/items"-->
                    <!--options-prop="title"-->
                <!--&gt;-->
                <!--</input-group>-->

                <!--<input-group-->
                    <!--v-if="action === 'insert'"-->
                    <!--label="Parent Id:"-->
                    <!--:model.sync="item.parent_id"-->
                    <!--:enter="enter"-->
                    <!--id="new-item-parent-id"-->
                    <!--tooltip-message="To make a new item in the current location, leave this field empty."-->
                <!--&gt;-->
                <!--</input-group>-->

                <!--&lt;!&ndash;Parent for updating item&ndash;&gt;-->
                <!--<input-group-->
                    <!--v-if="action === 'update'"-->
                    <!--label="New Parent:"-->
                    <!--:model.sync="item.newParent"-->
                    <!--:enter="enter"-->
                    <!--id="selected-item-new-parent"-->
                    <!--url="/api/items"-->
                    <!--options-prop="title"-->
                <!--&gt;-->
                <!--</input-group>-->

                <!--<input-group-->
                    <!--v-if="action === 'update'"-->
                    <!--label="Parent Id:"-->
                    <!--:model.sync="item.parent_id"-->
                    <!--:enter="enter"-->
                    <!--id="selected-item-parent-id"-->
                    <!--tooltip-message="To move or leave item at the top level, this field should be empty"-->
                <!--&gt;-->
                <!--</input-group>-->
            <!--</f7-tab>-->
        </f7-tabs>


    </div>



</template>

<script>
    import store from '../repositories/Store'
    var $ = require('jquery');
    export default {
        data: function () {
            return {
                shared: store.state,
                store: store,
                tab: 1
            };
        },
        computed: {
            titleId: function () {
                if (this.action === 'insert') {
                    return 'new-item-title';
                }
                return 'selected-item-title';
            },
//            isFavourite: function () {
//                if (this.item.favourite) {
//                    return true;
//                }
//                return false;
//            },
            type () {
                if (this.action === 'insert') {
                    return 'new';
                }
                else if (this.action === 'update') {
                    return 'selected'
                }
            }
        },
        methods: {
            dateChosen: function (date, id) {
                this.item.notBefore = date;
                store.set(date, this.storePath['notBefore']);
            },
            onSelected: function (field, option) {
//                console.log(field, option);
//                this.item.notBefore = date;
//                store.set(date, this.storePath['notBefore']);
            },
            /**
             * Make the selected item a favourite item if it wasn't already, and vice versa
             */
//            toggleFavourite () {
//                store.set(!this.item.favourite, 'selectedItemClone.favourite');
//            },

            setTab: function (number) {
                this.tab = number;
            },
            listen: function () {
                var that = this;
//                this.$on('set-tab', function (number) {
//                    this.setTab(number);
//                });
                $(document).on('set-tab', function (event, number) {
                   that.setTab(number);
                });
            }
        },
        mounted: function () {
            this.listen();
        },
//        created: function () {
//            this.$bus.$on('date-chosen', this.dateChosen);
//        },
        props: [
            'item',
            'showNewItemFields',
            //Insert or update
            'action',
            //Show the field or not
            'show',
            'enter',
            'focus',
            //Path to item in store
            'storePath'
        ]
    }
</script>