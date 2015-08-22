
<div class="item">

    @include('templates.lists.item.before-item')

    <div
        ng-if="!item.html"
        ng-click="showItemPopup(item)"
        class="item-content">

        <div class="title">[[item.title]]</div>

        <i ng-if="item.body" class="fa fa-sticky-note note"></i>

    </div>

    <div
        ng-if="item.html"
        ng-click="showItemPopup(item)"
        ng-bind-html="item.html"
        class="item-content">

        <div class="note">
            <i ng-if="item.body" class="fa fa-sticky-note"></i>
        </div>
    </div>

    @include('templates.lists.item.after-item')

</div>

@include('templates.lists.item.children')
