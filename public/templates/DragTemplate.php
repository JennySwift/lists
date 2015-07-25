<div
    ng-if="!item.html"
    ng-mousedown="mouseDown($event, item)"
    ng-mouseover="mouseOver(item)"
    class="item-content">
    [[item.title]]
</div>

<div
    ng-if="item.html"
    ng-mousedown="mouseDown($event, item)"
    ng-bind-html="item.html"
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
    </ul>
</div>


