<template>
    <div>
        <f7-toolbar tabbar>
            <f7-link tab-link-active tab-link=".tab1">Main</f7-link>
            <f7-link tab-link=".tab2">Note</f7-link>
            <f7-link tab-link=".tab3">Parent</f7-link>
            <f7-link tab-link=".tab4">Advanced</f7-link>
        </f7-toolbar>

        <f7-tabs>
            <f7-tab class="tab1" tab-active>

                <f7-list no-hairlines-md contacts-list>

                    <f7-list-item>
                        <f7-label>Title</f7-label>
                        <f7-input type="text" :value="item.title" @input="item.title = $event.target.value" @input:clear="item.title = ''" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item>
                        <f7-label>Priority</f7-label>
                        <f7-input type="text" :value="item.priority" @input="item.priority = $event.target.value" @input:clear="item.priority = ''" clear-button=""></f7-input>
                    </f7-list-item>

                    <f7-list-item title="Category" link popup-open=".selector-popup">
                        <div slot="after">{{item.category.data.name}}</div>
                    </f7-list-item>
                    <selector :model.sync="item.category.data"></selector>
                    <!--<li v-if="shared.categories.length > 0 && item.category">-->
                        <!--<a class="item-link smart-select smart-select-init" data-open-in="popup" data-close-on-select="true" data-searchbar="true">-->
                            <!--<select v-model="item.category.id" name="categories">-->
                                <!--<option v-for="category in shared.categories" :key="category.id" :value="category.id" :selected="category.id === item.category_id">{{category.name}}</option>-->
                            <!--</select>-->
                            <!--<div class="item-content">-->
                                <!--<div class="item-inner">-->
                                    <!--<div class="item-title">Category</div>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</a>-->
                    <!--</li>-->

                </f7-list>

                <!--<input-group-->
                    <!--label="Category:"-->
                    <!--:model.sync="item.category"-->
                    <!--:enter="enter"-->
                    <!--:id="type + '-item-category'"-->
                    <!--:options="shared.categories"-->
                    <!--options-prop="name"-->
                    <!--required="true"-->
                    <!--top-border="true"-->
                <!--&gt;-->
                <!--</input-group>-->
            </f7-tab>

            <f7-tab class="tab2">
               <f7-list no-hairlines-md contacts-list>
                   <f7-list-item>
                       <f7-label>Note</f7-label>
                       <f7-input type="text" :value="item.body" @input="item.body = $event.target.value" @input:clear="item.body = ''" clear-button=""></f7-input>
                   </f7-list-item>
               </f7-list>
            </f7-tab>

            <f7-tab class="tab3">
                <!--Not before-->
                <date-picker
                    :function-on-enter="enter"
                    :initial-date-value="item.notBefore"
                    :input-id="type + '-item-not-before'"
                    label="Not Before"
                    input-placeholder=""
                    property="notBefore"
                >
                </date-picker>

                <!--Recurring unit-->
                <input-group
                    label="RU:"
                    :model.sync="item.recurringUnit"
                    :enter="enter"
                    :id="type + '-item-recurring-unit'"
                    :options="shared.recurringUnits"
                    tooltip-message="Recurring Unit"
                >
                </input-group>

                <f7-list no-hairlines-md contacts-list>
                    <f7-list-item>
                        <f7-label>Recurring Frequency</f7-label>
                        <f7-input type="text" :value="item.recurringFrequency" @input="item.recurringFrequency = $event.target.value" @input:clear="item.recurringFrequency = ''" clear-button=""></f7-input>
                    </f7-list-item>
                </f7-list>
            </f7-tab>

            <f7-tab class="tab4">
                <!--Parent for inserting item-->
                <input-group
                    v-if="action === 'insert'"
                    label="Parent:"
                    :model.sync="item.parent"
                    :enter="enter"
                    id="new-item-parent"
                    url="/api/items"
                    options-prop="title"
                >
                </input-group>

                <input-group
                    v-if="action === 'insert'"
                    label="Parent Id:"
                    :model.sync="item.parent_id"
                    :enter="enter"
                    id="new-item-parent-id"
                    tooltip-message="To make a new item in the current location, leave this field empty."
                >
                </input-group>

                <!--Parent for updating item-->
                <input-group
                    v-if="action === 'update'"
                    label="New Parent:"
                    :model.sync="item.newParent"
                    :enter="enter"
                    id="selected-item-new-parent"
                    url="/api/items"
                    options-prop="title"
                >
                </input-group>

                <input-group
                    v-if="action === 'update'"
                    label="Parent Id:"
                    :model.sync="item.parent_id"
                    :enter="enter"
                    id="selected-item-parent-id"
                    tooltip-message="To move or leave item at the top level, this field should be empty"
                >
                </input-group>
            </f7-tab>
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
        props: [
            'item',
            'showNewItemFields',
            //Insert or update
            'action',
            //Show the field or not
            'show',
            'enter',
            'focus'
        ]
    }
</script>