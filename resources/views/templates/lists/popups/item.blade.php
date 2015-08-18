

<div
    ng-show="show.popups.item"
    ng-click="closePopup($event, 'item')"
    class="popup-outer">

	<div id="item-popup" class="popup-inner">
        <h3>[[itemPopup.title]]</h3>
        <h3>[[itemPopup.id]]</h3>
        <p>[[itemPopup.body]]</p>

        <select
                ng-model="itemPopup.category_id"
                ng-change="updateItemCategory()">
            <option
                    ng-repeat="category in categories"
                    ng-value="category.id"
                    ng-selected="category.id == itemPopup.category_id">
                [[category.name]]
            </option>
        </select>

        <input ng-model="itemPopup.priority" type="number" placeholder="priority"/>

        <button ng-click="updateItem()" class="btn btn-success">Save</button>

	</div>

</div>