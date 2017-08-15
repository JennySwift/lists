<template>

    <div>

        <ul class="nav nav-tabs">
            <li v-on:click="tab = 1" v-bind:class="{'active': tab === 1}"><a href="javascript:void(0)">Main</a></li>
            <li v-on:click="tab = 2" v-bind:class="{'active': tab === 2}"><a href="javascript:void(0)">Note</a></li>
            <li v-on:click="tab = 4" v-bind:class="{'active': tab === 4}"><a href="javascript:void(0)">Parent</a></li>
            <li v-on:click="tab = 3" v-bind:class="{'active': tab === 3}"><a href="javascript:void(0)">Advanced</a></li>
        </ul>

        <div class="item-fields">
            <div v-show="tab === 1" class="input-group-container">
                <!--Title-->
                <div class="form-group">
                    <label for="new-item-title">Title</label>
                    <textarea
                        v-model="item.title"
                        v-on:keyup.13="enter()"
                        :id="titleId"
                        class="item-title"
                    >
                    </textarea>
                </div>

                <input-group
                    label="Category:"
                    :model.sync="item.category"
                    :enter="enter"
                    :id="type + '-item-category'"
                    :options="shared.categories"
                    options-prop="name"
                    required="true"
                    top-border="true"
                >
                </input-group>
                
                <!--<pre>Item from item fields: @{{item}}</pre>-->

                <!--Priority-->
                <input-group
                    label="Priority:"
                    :model.sync="item.priority"
                    :enter="enter"
                    id="new-item-priority"
                    required="true"
                >
                </input-group>
            </div>

            <!--Body-->
            <div v-show="tab === 2" class="form-group item-body">
                <!--<label for="new-item-body">Body</label>-->
                <textarea
                    v-model="item.body"
                    class="note"
                    name="new-item-body"
                >
                </textarea>
            </div>

            <div v-show="tab === 3" class="input-group-container">
                <!--Urgency-->
                <!--<input-group-->
                    <!--label="Urgency:"-->
                    <!--:model.sync="item.urgency"-->
                    <!--:enter="enter"-->
                    <!--id="new-item-urgency"-->
                <!--&gt;-->
                <!--</input-group>-->

                <!--&lt;!&ndash;Alarm&ndash;&gt;-->
                <!--<date-picker-->
                    <!--:function-on-enter="enter"-->
                    <!--:initial-date-value.sync="item.alarm"-->
                    <!--input-id="new-item-alarm"-->
                    <!--label="Alarm"-->
                    <!--input-placeholder=""-->
                    <!--property="alarm"-->
                <!--&gt;-->
                <!--</date-picker>-->

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

                <!--Recurring Frequency-->
                <input-group
                    label="RF:"
                    :model.sync="item.recurringFrequency"
                    :enter="enter"
                    id="new-item-recurring-frequency"
                    tooltip-message="Recurring Frequency"
                >
                </input-group>
            </div>

            <div v-show="tab === 4" class="input-group-container">
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
                    tooltip-message="To move home, make field empty"
                >
                </input-group>
            </div>
        </div>


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