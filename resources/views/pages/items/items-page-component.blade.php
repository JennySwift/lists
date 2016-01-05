
<script id="items-template" type="x-template">

    <loading :show-loading="showLoading"></loading>
    @include('pages.items.pinned-items')
    <item-popup
        :show-item-popup.sync="showItemPopup"
        :selected-item="selectedItem"
        :categories="categories"
        :close-popup="closePopup"
    >
    </item-popup>

    <div id="lists" class="container">
        @include('pages.items.breadcrumb')
        @include('pages.items.search')
        @include('pages.items.new-item')
        @include('pages.items.items')

    </div>

</script>