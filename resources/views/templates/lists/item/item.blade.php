
<div class="item">

    @include('templates.lists.item.before-item')

    <sortable-directive
        something="item"
        items="items"
        newIndex="newIndex">
    </sortable-directive>

    <div class="item-id">
        <span class="badge">ID: [[item.id]]</span>
    </div>

    <div class="category">
        <span>[[item.category.name]]</span>
    </div>

    <div class="priority">
        <span class="badge">[[item.priority]]</span>
    </div>

    <div class="note">
        <i ng-if="item.body" class="fa fa-sticky-note-o"></i>
    </div>

    <button ng-click="showItemPopup(item)" class="btn btn-xs">view</button>

</div>

@include('templates.lists.item.children')
