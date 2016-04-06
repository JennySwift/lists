
<script id="items-page-template" type="x-template">

    <div>
        {{--Popup--}}
        <item-popup
                :categories="categories"
                :items.sync="items"
                :get-items="getItems"
                :recurring-units="recurringUnits"
        >
        </item-popup>

        {{--Alarms--}}
        <alarms
                :show-loading.sync="showLoading"
                :show-item-popup.sync="showItemPopup"
                :selected-item.sync="selectedItem"
                :categories="categories"
                :close-popup="closePopup"
        >
        </alarms>

        {{--Urgent items--}}
        <urgent-items
                :items-filter="itemsFilter"
                :title-filter="titleFilter"
                :priority-filter="priorityFilter"
                :category-filter="categoryFilter"
                :item="item"
                :categories="categories"
        >
        </urgent-items>

        <div id="lists" class="container">
            @include('pages.items.breadcrumb')

            {{--Favourites--}}
            <favourite-items
            >
            </favourite-items>

            {{--Filter--}}
            <filter
                :categories="categories"
                :filters.sync="filters"
                :items.sync="items"
            >
            </filter>

            {{--New item--}}
            <new-item
                :items.sync="items"
                :categories="categories"
                :zoomed-item="zoomedItem"
                :recurring-units="recurringUnits"
            >
            </new-item>

            {{--Items--}}
            <ul id="items">

                <item
                        v-for="item in items | itemsFilter"
                        :items-filter="itemsFilter"
                        :filters="filters"
                        :show-children="showChildren"
                        :items.sync="items"
                        :item="item"
                        :zoomed-item="zoomedItem"
                        :get-items="getItems"
                        :categories="categories"
                        :zoom="zoom"
                        class="item-with-children"
                >
                </item>

                <div v-if="items.length === 0">No items here</div>
            </ul>

        </div>

    </div>

</script>