
<script id="items-template" type="x-template">

    <loading :show-loading="showLoading"></loading>
    @include('pages.items.pinned-items')
    @include('pages.items.alarms')

    <item-popup
        :show-loading="showLoading"
        :show-item-popup.sync="showItemPopup"
        :selected-item="selectedItem"
        :categories="categories"
        :close-popup="closePopup"
        :items.sync="items"
        :get-items="getItems"
    >
    </item-popup>

    <div id="lists" class="container">
        @include('pages.items.breadcrumb')
        @include('pages.items.search')
        @include('pages.items.new-item')
        @include('pages.items.items')

    </div>

</script>