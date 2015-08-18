

<div
    ng-show="show.popups.item"
    ng-click="closePopup($event, 'item')"
    class="popup-outer">

	<div id="item-popup" class="popup-inner">

        <textarea
            ng-model="itemPopup.title"
            cols="30"
            rows="10">
            [[itemPopup.title]]
        </textarea>

        <h3>[[itemPopup.id]]</h3>

        <textarea
            ng-model="itemPopup.body"
            cols="30"
            rows="10">
            [[itemPopup.body]]
        </textarea>

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