<ul v-on:mousemove="mouseMove($event)" id="items">
    {{--Only apply filter here if home--}}
    <li
        v-if="breadcrumb.length > 0"
        v-for="item in items"
        ng-include src="'ItemTemplate'"
        class="item-with-children">
    </li>

    <li
        v-if="!breadcrumb || breadcrumb.length < 1"
        v-for="item in items | filter: {priority: filterPriority, category_id: filterCategory, title: filterTitle}"
        ng-include src="'ItemTemplate'"
        class="item-with-children">
    </li>
</ul>