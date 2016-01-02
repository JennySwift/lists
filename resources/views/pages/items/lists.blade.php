<ul ng-mousemove="mouseMove($event)" id="items">
    {{--Only apply filter here if home--}}
    <li
        ng-if="breadcrumb.length > 0"
        ng-repeat="item in items"
        ng-include src="'ItemTemplate'"
        class="item-with-children">
    </li>

    <li
        ng-if="!breadcrumb || breadcrumb.length < 1"
        ng-repeat="item in items | filter: {priority: filterPriority, category_id: filterCategory, title: filterTitle}"
        ng-include src="'ItemTemplate'"
        class="item-with-children">
    </li>
</ul>