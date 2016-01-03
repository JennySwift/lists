<ul id="items">

    <item
        {{--v-for="item in items | filterBy filterTitle in 'title'"--}}
        v-for="item in items | itemsFilter | orderBy 'priority'"
        :show-loading.sync="showLoading"
        :show-item-popup="showItemPopup"
        :show-children="showChildren"
        :items.sync="items"
        :item="item"
        :item-popup="itemPopup"
        :zoomed-item="zoomedItem"
        :categories="categories"
        :zoom="zoom"
        class="item-with-children"
    >
    </item>

    <div v-if="items.length === 0">No items here</div>
</ul>