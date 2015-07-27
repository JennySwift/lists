<div id="breadcrumb">
    <div>
        <a ng-click="goHome()">Home</a>
        <i ng-if="breadcrumb.length > 0" class="fa fa-angle-right"></i>
    </div>
    <div ng-repeat="item in breadcrumb">
        <a ng-click="zoom(item)">[[item.title]]</a>
        <i ng-if="!$last" class="fa fa-angle-right"></i>
    </div>
</div>