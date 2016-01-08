
<script id="items-template" type="x-template">

    <loading :show-loading="showLoading"></loading>

{{--    @include('pages.items.pinned-items')--}}
    @include('pages.items.alarms')
    @include('pages.items.urgent-items')
    @include('pages.items.item-popup')

    <div id="lists" class="container">
        @include('pages.items.breadcrumb')
        @include('pages.items.search')
        @include('pages.items.new-item')
        @include('pages.items.items')
    </div>

</script>