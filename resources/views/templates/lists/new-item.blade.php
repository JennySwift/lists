<div id="new-item">
    <input ng-keyup="insertItem($event.keyCode)" ng-model="new_item.title" type="text" placeholder="title"/>
    <input ng-keyup="insertItem($event.keyCode)" ng-model="new_item.body" type="text" placeholder="body"/>
    <button ng-click="insertItem(13)" class="btn btn-success">Add item</button>
</div>