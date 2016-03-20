
<script id="items-page-template" type="x-template">

    <div>
        <loading :show-loading="showLoading"></loading>

        @include('pages.items.alarms')

        <urgent-items
                {{--:items-filter="itemsFilter"--}}
                {{--:title-filter="titleFilter"--}}
                {{--:priority-filter="priorityFilter"--}}
                {{--:category-filter="categoryFilter"--}}
                :show-loading.sync="showLoading"
                :show-item-popup.sync="showItemPopup"
                {{--:item="item"--}}
                :categories="categories"
                :selected-item.sync="selectedItem"
                class="item-with-children"
        >
        </urgent-items>

        @include('pages.items.item-popup')

        <div id="lists" class="container">
            @include('pages.items.breadcrumb')

            <filter
                :categories="categories"
                :favourite-items="favouriteItems"
                :filters.sync="filters"
            >
            </filter>

            <new-item
                :items.sync="items"
                :categories="categories"
            >
            </new-item>

            @include('pages.items.items')
        </div>

    </div>

</script>