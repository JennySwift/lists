<div id="new-item">

    <div>
        <label for="new-item-title">Title</label>
        <input
            ng-model="newItem.title"
            ng-keyup="insertItem($event.keyCode)"
            type="text"
            id="new-item-title"
            name="new-item-title"
            placeholder="title"
            class="form-control"
        >
    </div>

    <div>
        <label for="new-item-body">Body</label>
        <textarea
                ng-model="newItem.body"
                placeholder="note"
                class="note"
                name="new-item-body"
        >
        </textarea>
    </div>

    <div>
        <label for="new-item-priority">Priority</label>
        <input
            ng-model="newItem.priority"
            ng-keyup="insertItem($event.keyCode)"
            type="number"
            id="new-item-priority"
            name="new-item-priority"
            placeholder="priority"
            class="form-control priority"
        >
    </div>

    <div>
        <label for="new-item-category">Category</label>
        <select
                ng-keyup="insertItem($event.keyCode)"
                ng-model="newItem.category_id"
                class="form-control"
                name="new-item-category"
        >
            <option
                    ng-repeat="category in categories"
                    ng-value="category.id">
                [[category.name]]
            </option>
        </select>
    </div>


    <div>
        <label for="new-item-pinned">Pinned</label>
        <input
            ng-model="newItem.pinned"
            ng-keyup="insertItem($event.keyCode)"
            type="checkbox"
            id="new-item-pinned"
            name="new-item-pinned"
            placeholder="pinned"
            class="form-control"
        >
    </div>

    <div>
        <button ng-click="insertItem(13)" class="btn btn-success">Add item</button>
    </div>

</div>