<div
    ng-if="!item.html"
    ng-mousedown="mouseDown($event, item)"
    ng-mouseover="mouseOver(item, $event)"
    ng-mouseleave="mouseLeave(item, $event)"
    class="item-content">
    [[item.id]]
</div>

<div
    ng-if="item.html"
    ng-mousedown="mouseDown($event, item)"
    ng-mouseover="mouseOver(item, $event)"
    ng-mouseleave="mouseLeave(item, $event)"
    ng-bind-html="item.id"
    class="item-content">
</div>

<!--<div class="debug-info">-->
<!--    <span></span>-->
<!--</div>-->

<div class="debug-info">
    <ul class="list-group">
        <li class="list-group-item">
            diffY:
            <span class="badge">[[diffY]]</span>
        </li>
        <li class="list-group-item">
            newIndex:
            <span class="badge">[[newIndex]]</span>
        </li>
        <li class="list-group-item">
            newParentId:
            <span class="badge">[[newParent.id]]</span>
        </li>
    </ul>
</div>


