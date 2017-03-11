<template>

    <div>

        <ul class="nav nav-tabs">
            <li role="presentation" v-on:click="tab = 1" v-bind:class="{'active': tab === 1}"><a href="#">Main</a></li>
            <li role="presentation" v-on:click="tab = 2" v-bind:class="{'active': tab === 2}"><a href="#">Note</a></li>
            <li role="presentation" v-on:click="tab = 3" v-bind:class="{'active': tab === 3}"><a href="#">Advanced</a></li>
        </ul>

        <div v-show="tab === 1">
            <!--Title-->
            <input-group
                label="Title:"
                :model.sync="item.title"
                :enter="enter"
                id="new-item-title"
                required="true"
                :focus="focus"
            >
            </input-group>

            <input-group
                v-show="show"
                label="Category:"
                :model.sync="item.category"
                :enter="enter"
                id="new-item-category"
                :options="shared.categories"
                options-prop="name"
                required="true"
            >
            </input-group>

            <!--Priority-->
            <input-group
                v-show="show"
                label="Priority:"
                :model.sync="item.priority"
                :enter="enter"
                id="new-item-priority"
                required="true"
            >
            </input-group>
        </div>

        <!--Body-->
        <div v-show="show && tab === 2" class="form-group item-body">
            <!--<label for="new-item-body">Body</label>-->
            <textarea
                v-model="item.body"
                class="note"
                name="new-item-body"
            >
            </textarea>
        </div>

        <div v-show="show && tab === 3">
            <!--Urgency-->
            <input-group
                v-show="show"
                label="Urgency:"
                :model.sync="item.urgency"
                :enter="enter"
                id="new-item-urgency"
                top-border="true"
            >
            </input-group>

            <!--Alarm-->
            <date-picker
                v-show="show"
                :function-on-enter="enter"
                :chosen-date.sync="item.alarm"
                input-id="new-item-alarm"
                label="Alarm"
                input-placeholder=""
                property="alarm"
            >
            </date-picker>

            <!--Not before-->
            <date-picker
                v-show="show"
                :function-on-enter="enter"
                :chosen-date.sync="item.notBefore"
                input-id="new-item-not-before"
                label="Not Before"
                input-placeholder=""
                property="notBefore"
            >
            </date-picker>

            <!--Recurring unit-->
            <input-group
                v-show="show"
                label="RU:"
                :model.sync="item.recurringUnit"
                :enter="enter"
                id="new-item-recurring-unit"
                :options="shared.recurringUnits"
                tooltip-id="selected-item-recurring-unit-tooltip"
            >
            </input-group>

            <!--Recurring Frequency-->
            <input-group
                v-show="show"
                label="RF:"
                :model.sync="item.recurringFrequency"
                :enter="enter"
                id="new-item-recurring-frequency"
                tooltip-id="selected-item-recurring-frequency-tooltip"
            >
            </input-group>

            <div class="tooltip_templates">
                <div id="selected-item-recurring-unit-tooltip">
                    Recurring Unit
                </div>
            </div>

            <div class="tooltip_templates">
                <div id="selected-item-recurring-frequency-tooltip">
                    Recurring Frequency
                </div>
            </div>

            <div v-if="action === 'update'">
                <input-group
                    label="New Parent:"
                    :model.sync="item.newParent"
                    :enter="updateItem"
                    id="selected-item-new-parent"
                    url="/api/items"
                    options-prop="title"
                >
                </input-group>

                <input-group
                    label="Parent Id:"
                    :model.sync="item.parent_id"
                    :enter="updateItem"
                    id="selected-item-parent-id"
                    tooltip-id="selected-item-parent-id-tooltip"
                >
                </input-group>

                <div class="tooltip_templates">
                    <div id="selected-item-parent-id-tooltip">
                        To move home, make field empty
                    </div>
                </div>
            </div>
        </div>
    </div>



</template>

<script>
    module.exports = {
        data: function () {
            return {
                shared: store.state,
                tab: 1
            };
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