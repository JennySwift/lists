<div class="item">

    <div class="before-item">
        <i ng-if="item.has_children && !item.children || item.children.length < 1" ng-click="getChildren(item)" class="fa fa-plus"></i>
        <i ng-if="item.has_children && item.children.length > 0" ng-click="collapseItem(item)" class="fa fa-minus"></i>
        <i ng-if="!item.has_children"></i>
    </div>

    <div>[[item.title]]</div>

</div>

<ul ng-if="item.children">
    <li ng-repeat="item in item.children" ng-include src="'ItemTemplate'"></li>
</ul>
