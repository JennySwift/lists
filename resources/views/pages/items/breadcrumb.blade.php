<div id="breadcrumb">
    <div>
        <a v-on:click="goHome()">Home</a>
        <i v-if="breadcrumb.length > 0" class="fa fa-angle-right"></i>
    </div>
    <div ng-repeat="item in breadcrumb">
        <a v-on:click="zoom(item)">@{{ item.title }}</a>
        <i v-if="!$last" class="fa fa-angle-right"></i>
    </div>
</div>