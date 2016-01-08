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

            <div class="flex">
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

                <div class="form-group">
                    <label for="selected-item-alarm">Alarm</label>
                    <input
                            v-model="selectedItem.alarm"
                            type="text"
                            id="selected-item-alarm"
                            name="selected-item-alarm"
                            placeholder="alarm"
                            class="form-control"
                    >
                </div>
            </div>

            <div class="flex">
                <div class="form-group">
                    <label for="selected-item-priority">Priority</label>
                    <input
                            v-model="selectedItem.priority"
                            type="number"
                            id="selected-item-priority"
                            name="selected-item-priority"
                            placeholder="priority"
                            class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="selected-item-urgency">Urgency</label>
                    <input
                            v-model="selectedItem.urgency"
                            type="number"
                            id="selected-item-urgency"
                            name="selected-item-urgency"
                            placeholder="urgency"
                            class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="selected-item-parent">Parent Id</label>
                    <input
                            v-model="selectedItem.parent_id"
                            type="text"
                            id="selected-item-parent"
                            name="selected-item-parent"
                            placeholder="parent.id"
                            class="form-control"
                    >
                </div>
            </div>

            <div class="buttons">
                <button v-on:click="showItemPopup = false" class="btn btn-danger">Cancel</button>
                <button v-on:click="updateItem(selectedItem)" class="btn btn-success">Save</button>
            </div>

        </div>

    </div>

</script>