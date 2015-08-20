<ul ng-mousemove="mouseMove($event)" id="items">
    <li ng-repeat="item in items | filter: {priority: filterPriority, category_id: filterCategory, title: filterTitle}"
        ng-include src="'ItemTemplate'"
        class="item-with-children">
    </li>
</ul>