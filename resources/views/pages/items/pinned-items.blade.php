<div v-if="pinnedItems.length > 0" id="pinned-items">
    <h5>Pinned Items</h5>

    <item
            {{--v-for="item in items | filterBy filterTitle in 'title'"--}}
            v-for="item in pinnedItems | orderBy 'priority'"
            :show-loading.sync="showLoading"
            :show-item-popup.sync="showItemPopup"
            :show-children="showChildren"
            :items.sync="items"
            :item="item"
            :selected-item="selectedItem"
            :zoomed-item="zoomedItem"
            :get-items="getItems"
            :categories="categories"
            :zoom="zoom"
            class="item-with-children"
    >
    </item>
</div>