<div class="before-item">

    <span class="badge">[[item.priority]]</span>

    <button ng-click="deleteItem(item)" class="btn-danger btn-xs delete-item">
        <span class="fa fa-times"></span>
    </button>

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
            ng-if="!item.has_children"
            class="fa fa-plus my-hidden">
    </i>

</div>
