<div class="guide"></div>

<div class="item"">

    <div class="before-item">
        <button ng-click="deleteItem(item)" class="btn-danger btn-xs delete-item">delete</button>
        <i
            ng-click="zoom(item)"
            class="fa fa-search-plus">
        </i>
        <i
            ng-if="item.has_children && !item.children || item.children.length < 1"
            ng-click="getChildren(item)"
            class="fa fa-plus">
        </i>
        <i
            ng-if="item.has_children && item.children.length > 0"
            ng-click="collapseItem(item)"
            class="fa fa-minus">

        </i>
        <i
            ng-if="!item.has_children">
        </i>
        <span class="badge">#: [[item.index]]</span>
    </div>

    <drag-directive
        something="item"
        items="items"
        newIndex="newIndex">
    </drag-directive>

    <div>ID: [[item.id]]</div>

    <button ng-click="moveUp(item, $index)" class="btn btn-xs">move up</button>

</div>

<ul ng-if="item.children">
    <li
        ng-repeat="item in item.children"
        ng-include src="'ItemTemplate'">
    </li>
</ul>
