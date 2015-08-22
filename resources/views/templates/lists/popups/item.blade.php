
<div
    ng-show="show.popups.item"
    ng-click="closePopup($event, 'item')"
    class="popup-outer">

	<div id="item-popup" class="popup-inner">

        <button
            ng-if="!itemPopup.favourite"
            ng-click="itemPopup.favourite = !itemPopup.favourite"
            class="favourite fa fa-star-o">
        </button>

        <button
            ng-if="itemPopup.favourite"
            ng-click="itemPopup.favourite = !itemPopup.favourite"
            class="favourite fa fa-star">
        </button>

        <h3>Title (id: [[itemPopup.id]], parentId: [[itemPopup.parent_id]])</h3>

        <textarea
            ng-model="itemPopup.title"
            rows="2">
            [[itemPopup.title]]
        </textarea>

        <h3>Note</h3>

        <textarea
            ng-model="itemPopup.body"
            rows="10">
            [[itemPopup.body]]
        </textarea>

        <h3>Category</h3>

        <select
            ng-model="itemPopup.category_id"
            ng-change="updateItemCategory()"
            class="form-control">
            <option
                    ng-repeat="category in categories"
                    ng-value="category.id"
                    ng-selected="category.id == itemPopup.category_id">
                [[category.name]]
            </option>
        </select>

        <h3>Priority</h3>

        <input ng-model="itemPopup.priority" type="number" placeholder="priority"/>

        <button ng-click="updateItem()" class="btn btn-success save">Save</button>

	</div>

</div>