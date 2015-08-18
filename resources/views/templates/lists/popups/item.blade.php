

<div
    ng-show="show.popups.item"
    ng-click="closePopup($event, 'item')"
    class="popup-outer">

	<div id="item-popup" class="popup-inner">
        <h3>[[itemPopup.title]]</h3>
        <p>[[itemPopup.body]]</p>
	</div>

</div>