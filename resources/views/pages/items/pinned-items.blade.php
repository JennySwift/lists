<div id="pinned-items">
    <h5>Pinned Items</h5>
    <div
        v-for="item in pinnedItems"
        ng-include src="'ItemTemplate'"
    >
    </div>
</div>