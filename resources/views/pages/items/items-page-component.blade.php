
<script id="items-template" type="x-template">

    <loading :show-loading="showLoading"></loading>
    @include('pages.items.pinned-items')

    <div id="lists" class="container">
        @include('pages.items.breadcrumb')
        @include('pages.items.search')
        @include('pages.items.new-item')
        @include('pages.items.items')

    </div>

</script>