<div
    ng-if="!item.html"
    ng-mouseover="mouseOver(item, $event)"
    ng-mouseleave="mouseLeave(item, $event)"
    class="item-content">
    [[item.title]]
</div>

<div
    ng-if="item.html"
    ng-mouseover="mouseOver(item, $event)"
    ng-mouseleave="mouseLeave(item, $event)"
    ng-bind-html="item.html"
    class="item-content">
</div>

@include('templates/lists/debug-info')


