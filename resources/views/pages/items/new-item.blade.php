<div id="new-item">
    <input
        ng-keyup="insertItem($event.keyCode)"
        ng-model="new_item.title"
        type="text"
        placeholder="title"/>

    <textarea
        ng-model="new_item.body"
        placeholder="note"
        class="note">
    </textarea>

    <input
            ng-keyup="insertItem($event.keyCode)"
            ng-model="new_item.priority"
            type="number"
            placeholder="priority"
            class="priority"/>

    <select
        ng-keyup="insertItem($event.keyCode)"
        ng-model="new_item.category_id"
        class="form-control">
        <option
            ng-repeat="category in categories"
            ng-value="category.id">
            [[category.name]]
        </option>
    </select>

    <div>
        <button ng-click="insertItem(13)" class="btn btn-success">Add item</button>
    </div>

</div>