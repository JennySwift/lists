<div id="new-item">

    {{--First column--}}
    <div>
        {{--Title--}}
        <div class="form-group">
            <label for="new-item-title">Title <span class="fa fa-asterisk"></span></label>
            <input
                    v-model="newItem.title"
                    v-on:keyup.13="insertItem()"
                    v-on:focus="showNewItemFields = true"
                    type="text"
                    id="new-item-title"
                    name="new-item-title"
                    placeholder="title"
                    class="form-control"
            >
        </div>

        {{--Body--}}
        <div v-show="showNewItemFields" class="form-group">
            <label for="new-item-body">Body</label>
            <textarea
                    v-model="newItem.body"
                    placeholder="note"
                    class="note"
                    name="new-item-body"
            >
            </textarea>
        </div>
    </div>

    {{--Second column--}}

    <div>
        {{--Category--}}
        <div v-show="showNewItemFields" class="form-group">
            <label for="new-item-category">Category <span class="fa fa-asterisk"></span></label>

            <select
                    v-model="newItem.category"
                    v-on:keyup.13="insertItem()"
                    id="new-item-category"
                    class="form-control"
            >
                <option
                        v-for="category in categories"
                        v-bind:value="category"
                >
                    @{{ category.name }}
                </option>
            </select>
        </div>

        {{--Priority--}}
        <div v-show="showNewItemFields" class="form-group">
            <label for="new-item-priority">Priority <span class="fa fa-asterisk"></span></label>
            <input
                    v-model="newItem.priority"
                    v-on:keyup.13="insertItem()"
                    type="number"
                    id="new-item-priority"
                    name="new-item-priority"
                    placeholder=""
                    class="form-control priority"
            >
        </div>

        {{--Urgency--}}
        <div v-show="showNewItemFields" class="form-group">
            <label for="new-item-urgency">Urgency</label>
            <input
                    v-model="newItem.urgency"
                    v-on:keyup.13="insertItem()"
                    type="number"
                    id="new-item-urgency"
                    name="new-item-urgency"
                    placeholder=""
                    class="form-control urgency"
            >
        </div>

        {{--Alarm--}}
        <div v-show="showNewItemFields" class="form-group">
            <label for="new-item-alarm">Alarm</label>
            <input
                    v-model="newItem.alarm"
                    v-on:keyup.13="insertItem()"
                    type="text"
                    id="new-item-alarm"
                    name="new-item-alarm"
                    placeholder="alarm"
                    class="form-control"
            >
        </div>

        {{--Pinned--}}
        <div v-show="showNewItemFields" class="form-group">
            <label for="new-item-pinned">Pinned</label>
            <input
                    v-model="newItem.pinned"
                    v-on:keyup.13="insertItem()"
                    type="checkbox"
                    id="new-item-pinned"
                    name="new-item-pinned"
                    placeholder="pinned"
                    class="form-control"
            >
        </div>

        <div v-show="showNewItemFields" class="form-group">
            <button
                    v-on:click="insertItem(13)"
                    :disabled="!newItem.title || !newItem.category || !newItem.priority"
                    class="btn btn-success"
            >
                Add item
            </button>
            <button
                    v-on:click="showNewItemFields = false"
                    class="btn btn-default"
            >
                Cancel
            </button>
        </div>
    </div>

</div>