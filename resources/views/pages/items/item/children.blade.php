<ul v-if="item.children">
    <li
        v-for="item in item.children | filter: {priority: filterPriority, category_id: filterCategory, title: filterTitle}"
        ng-include src="'ItemTemplate'">
    </li>
</ul>