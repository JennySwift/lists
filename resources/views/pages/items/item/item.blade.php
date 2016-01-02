
<div class="item">

    @include('pages.items.item.before-item')

    <div
        v-if="!item.html"
        v-on:click="showItemPopup(item)"
        class="item-content">

        <div class="title">@{{ item.title }}</div>

        <i v-if="item.body" class="fa fa-sticky-note note"></i>
        <i v-if="item.pinned" class="fa fa-map-pin pinned"></i>

    </div>

    <div
        v-if="item.html"
        v-on:click="showItemPopup(item)"
        ng-bind-html="item.html"
        class="item-content">

        <div class="note">
            <i v-if="item.body" class="fa fa-sticky-note"></i>
        </div>

        <div class="pinned">
            <i v-if="item.pinned" class="fa fa-map-pin"></i>
        </div>
    </div>

    @include('pages.items.item.after-item')

</div>

@include('pages.items.item.children')
