<ul id="items">
    
{{--    <pre>@{{$data.itemsFilter | json}}</pre>--}}

    <item
        v-for="item in items | itemsFilter"
        :items-filter="itemsFilter"
        :title-filter="titleFilter"
        :priority-filter="priorityFilter"
        :category-filter="categoryFilter"
        :show-loading.sync="showLoading"
        :show-item-popup.sync="showItemPopup"
        :show-children="showChildren"
        :items.sync="items"
        :item="item"
        :selected-item.sync="selectedItem"
        :zoomed-item="zoomedItem"
        :get-items="getItems"
        :categories="categories"
        :zoom="zoom"
        :delete-item="deleteItem"
        class="item-with-children"
    >
    </item>

    <div v-if="items.length === 0">No items here</div>
</ul>