<div id="new-item">
    <input
        ng-keyup="insertItem($event.keyCode)"
        ng-model="new_item.title"
        type="text"
        placeholder="title"/>

    <input
        ng-keyup="insertItem($event.keyCode)"
        ng-model="new_item.body"
        type="text"
        placeholder="body"/>

    <input
            ng-keyup="insertItem($event.keyCode)"
            ng-model="new_item.priority"
            type="number"
            placeholder="priority"/>

    <select
        ng-keyup="insertItem($event.keyCode)"
        ng-model="new_item.category_id">
        <option
            ng-repeat="category in categories"
            ng-value="category.id">
            [[category.name]]
        </option>
    </select>

    <button ng-click="insertItem(13)" class="btn btn-success">Add item</button>
</div>