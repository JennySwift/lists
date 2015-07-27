
<div class="item">

    @include('templates.lists.item.before-item')

    <sortable-directive
        something="item"
        items="items"
        newIndex="newIndex">
    </sortable-directive>

    <span class="badge">ID: [[item.id]]</span>

    <button ng-click="moveUp(item, $index)" class="btn btn-xs">move up</button>

</div>

@include('templates.lists.item.children')
