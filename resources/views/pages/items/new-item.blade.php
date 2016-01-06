<div id="new-item">

    <div class="flex">
        {{--Title--}}
        <div>
            <label for="new-item-title">Title</label>
            <input
                    v-model="newItem.title"
                    v-on:keyup="insertItem($event.keyCode)"
                    type="text"
                    id="new-item-title"
                    name="new-item-title"
                    placeholder="title"
                    class="form-control"
            >
        </div>

        {{--Body--}}
        <div>
            <label for="new-item-body">Body</label>
            <textarea
                    v-model="newItem.body"
                    placeholder="note"
                    class="note"
                    name="new-item-body"
            >
            </textarea>
        </div>

        {{--Category--}}
        <div class="form-group">
            <label for="new-item-category">Category</label>

            <select v-model="newItem.category" v-on:keyup="insertItem($event.keyCode)" id="new-item-category" class="form-control">
                <option v-for="category in categories" v-bind:value="category">
                    @{{ category.name }}
                </option>
            </select>
        </div>
    </div>

    <div class="flex">
        {{--Priority--}}
        <div>
            <label for="new-item-priority">Priority</label>
            <input
                    v-model="newItem.priority"
                    v-on:keyup="insertItem($event.keyCode)"
                    type="number"
                    id="new-item-priority"
                    name="new-item-priority"
                    placeholder=""
                    class="form-control priority"
            >
        </div>

        {{--Urgency--}}
        <div>
            <label for="new-item-urgency">Urgency</label>
            <input
                    v-model="newItem.urgency"
                    v-on:keyup="insertItem($event.keyCode)"
                    type="number"
                    id="new-item-urgency"
                    name="new-item-urgency"
                    placeholder=""
                    class="form-control urgency"
            >
        </div>
    </div>

    <div class="flex">
        {{--Alarm--}}
        <div class="form-group">
            <label for="new-item-alarm">Alarm</label>
            <input
                    v-model="newItem.alarm"
                    v-on:keyup="insertItem($event.keyCode)"
                    type="text"
                    id="new-item-alarm"
                    name="new-item-alarm"
                    placeholder="alarm"
                    class="form-control"
            >
        </div>

        {{--Pinned--}}
        <div>
            <label for="new-item-pinned">Pinned</label>
            <input
                    v-model="newItem.pinned"
                    v-on:keyup="insertItem($event.keyCode)"
                    type="checkbox"
                    id="new-item-pinned"
                    name="new-item-pinned"
                    placeholder="pinned"
                    class="form-control"
            >
        </div>

    </div>

    <div class="flex">
        <button v-on:click="insertItem(13)" class="btn btn-success">Add item</button>
    </div>

</div>