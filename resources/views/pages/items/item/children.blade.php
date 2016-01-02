<ul ng-if="item.children">
    <li
        ng-repeat="item in item.children | filter: {priority: filterPriority, category_id: filterCategory, title: filterTitle}"
        ng-include src="'ItemTemplate'">
    </li>
</ul>