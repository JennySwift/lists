
<div class="item">

    @include('pages.items.item.before-item')

    <div
        ng-if="!item.html"
        ng-click="showItemPopup(item)"
        class="item-content">

        <div class="title">[[item.title]]</div>

        <i ng-if="item.body" class="fa fa-sticky-note note"></i>
        <i ng-if="item.pinned" class="fa fa-map-pin pinned"></i>

    </div>

    <div
        ng-if="item.html"
        ng-click="showItemPopup(item)"
        ng-bind-html="item.html"
        class="item-content">

        <div class="note">
            <i ng-if="item.body" class="fa fa-sticky-note"></i>
        </div>

        <div class="pinned">
            <i ng-if="item.pinned" class="fa fa-map-pin"></i>
        </div>
    </div>

    @include('pages.items.item.after-item')

</div>

@include('pages.items.item.children')
