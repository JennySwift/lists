<ul id="items">
    {{--Only apply filter here if home--}}
    <item
        v-if="breadcrumb.length > 0"
        v-for="item in items"
        class="item-with-children"
    >
    </item>

    <item
        v-if="!breadcrumb || breadcrumb.length < 1"
        {{--v-for="item in items | filterBy filterTitle in 'title'"--}}
        v-for="item in items | itemsFilter"
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