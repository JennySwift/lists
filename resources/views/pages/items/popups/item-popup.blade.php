
<div
    v-show="showItemPopup"
    v-on:click="closePopup($event, 'showItemPopup')"
    class="popup-outer">

	<div id="item-popup" class="popup-inner">

        <button
            v-if="!itemPopup.favourite"
            v-on:click="itemPopup.favourite = !itemPopup.favourite"
            class="favourite fa fa-star-o">
        </button>

        <button v-on:click="deleteItem(itemPopup)" class="btn btn-danger delete-item">Delete</button>

        <button
            v-if="itemPopup.favourite"
            v-on:click="itemPopup.favourite = !itemPopup.favourite"
            class="favourite fa fa-star">
        </button>

        <button
                v-on:click="itemPopup.pinned = !itemPopup.pinned"
                ng-class="{'pinned': itemPopup.pinned, 'unpinned': !itemPopup.pinned}"
                class="pin-btn fa fa-map-pin">
        </button>

        <h3>Title (id: @{{ itemPopup.id }}, parentId: @{{ itemPopup.parent_id }}</h3>

        <textarea
            v-model="itemPopup.title"
            rows="2">
            [[itemPopup.title]]
        </textarea>

        <h3>Note</h3>

        <textarea
            v-model="itemPopup.body"
            rows="10">
            [[itemPopup.body]]
        </textarea>

        <h3>Category</h3>

        <div class="form-group">
            <label for="item-popup-category">Category</label>

            <select
                v-model="itemPopup.category"
                id="item-popup-category"
                class="form-control"
            >
                <option v-for="category in categories" v-bind:value="category">
                    @{{ category.name }}
                </option>
            </select>
        </div>

        <h3>Priority</h3>

        <input v-model="itemPopup.priority" type="number" placeholder="priority"/>

        <div class="buttons">
            <button v-on:click="showItemPopup = false" class="btn btn-danger">Cancel</button>
            <button v-on:click="updateItem(itemPopup)" class="btn btn-success">Save</button>
        </div>

	</div>

</div>