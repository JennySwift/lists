<template>
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

    <!--Body-->
    <div v-show="show" class="form-group item-body">
        <label for="new-item-body">Body</label>
        <textarea
            v-model="item.body"
            placeholder="note"
            class="note"
            name="new-item-body"
        >
                </textarea>
    </div>

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
        label="Recurring Unit:"
        :model.sync="item.recurringUnit"
        :enter="enter"
        id="new-item-recurring-unit"
        :options="shared.recurringUnits"
    >
    </input-group>

    <!--Recurring Frequency-->
    <input-group
        v-show="show"
        label="Recurring Frequency:"
        :model.sync="item.recurringFrequency"
        :enter="enter"
        id="new-item-recurring-frequency"
    >
    </input-group>
</template>

<script>
    module.exports = {
        data: function () {
            return {
                shared: store.state
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