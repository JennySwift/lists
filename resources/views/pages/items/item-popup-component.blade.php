<script id="item-popup-template" type="x-template">

    <div
        v-if="showItemPopup"
        v-on:click="closePopup($event, 'showItemPopup')"
        class="popup-outer">

        <div id="item-popup" class="popup-inner">

            <button
                    v-if="!selectedItem.favourite"
                    v-on:click="selectedItem.favourite = !selectedItem.favourite"
                    class="favourite fa fa-star-o">
            </button>

            <button v-on:click="deleteItem(selectedItem)" class="btn btn-danger delete-item">Delete</button>

            <button
                    v-if="selectedItem.favourite"
                    v-on:click="selectedItem.favourite = !selectedItem.favourite"
                    class="favourite fa fa-star">
            </button>

            <button
                    v-on:click="selectedItem.pinned = !selectedItem.pinned"
                    v-bind:class="{'pinned': selectedItem.pinned, 'unpinned': !selectedItem.pinned}"
                    class="pin-btn fa fa-map-pin">
            </button>

            <h3>Title (id: @{{ selectedItem.id }}, parentId: @{{ selectedItem.parent_id }}</h3>

        <textarea
                v-model="selectedItem.title"
                rows="2">
        </textarea>

            <h3>Note</h3>

        <textarea
                v-model="selectedItem.body"
                rows="10">
        </textarea>

            <h3>Category</h3>

            <div class="form-group">
                <label for="item-popup-category">Category</label>

                <select
                        v-model="selectedItem.category"
                        id="item-popup-category"
                        class="form-control"
                >
                    <option v-for="category in categories" v-bind:value="category">
                        @{{ category.name }}
                    </option>
                </select>
            </div>

            <h3>Priority</h3>

            <input v-model="selectedItem.priority" type="number" placeholder="priority"/>

            <input v-model="selectedItem.urgency" type="number" placeholder="urgency"/>

            <div class="buttons">
                <button v-on:click="showItemPopup = false" class="btn btn-danger">Cancel</button>
                <button v-on:click="updateItem(selectedItem)" class="btn btn-success">Save</button>
            </div>

        </div>

    </div>

</script>