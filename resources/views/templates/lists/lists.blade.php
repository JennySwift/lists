<ul ng-mousemove="mouseMove($event)" id="items">
    <li ng-repeat="item in items"
        ng-include src="'ItemTemplate'"
        class="item-with-children">
    </li>
</ul>