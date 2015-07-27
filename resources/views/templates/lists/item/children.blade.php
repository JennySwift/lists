<ul ng-if="item.children">
    <li
        ng-repeat="item in item.children"
        ng-include src="'ItemTemplate'">
    </li>
</ul>