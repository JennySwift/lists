<ul v-if="item.children.length > 0">

    <item
            v-if="!breadcrumb || breadcrumb.length < 1"
            {{--v-for="item in items | filterBy filterTitle in 'title'"--}}
            v-for="item in item.children | itemsFilter"
            :show-loading="showLoading"
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
</ul>