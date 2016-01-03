
<script id="items-template" type="x-template">

    <loading :show-loading="showLoading"></loading>

    <div id="lists" class="container">
        @include('pages.items.breadcrumb')
        @include('pages.items.search')
        @include('pages.items.new-item')
        @include('pages.items.lists')

    </div>

</script>