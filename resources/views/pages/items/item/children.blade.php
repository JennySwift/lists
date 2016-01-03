<ul v-if="item.children.length > 0">

    <item
            v-if="!breadcrumb || breadcrumb.length < 1"
            {{--v-for="item in items | filterBy filterTitle in 'title'"--}}
            v-for="item in item.children | itemsFilter"
            :show-loading.sync="showLoading"
            :show-item-popup.sync="showItemPopup"
            :show-children="showChildren"
            :items.sy="items"
            :item="item"
            :item-popup="itemPopup"
            :zoomed-item="zoomedItem"
            :get-items="getItems"
            :categories="categories"
            :zoom="zoom"
            class="item-with-children"
    >
    </item>
</ul>