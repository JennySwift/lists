<!DOCTYPE html>
<html lang="en" ng-app="lists">
<head>
    <meta charset="UTF-8">
    <title>Lists</title>
    <?php
    include(base_path() . '/resources/views/templates/config.php');
    include($head_links);
    ?>
</head>
<body>

@include('templates.header')

<div ng-controller="ListsController" id="lists" class="container">

    <div id="search-container">
        <input ng-keyup="filter($event.keyCode)" type="text" placeholder="search" id="filter"/>
    </div>

    <div id="new-item">
        <input ng-model="new_item.title" type="text" placeholder="title"/>
        <input ng-model="new_item.body" type="text" placeholder="body"/>
        <button ng-click="insertItem()" class="btn btn-success">Add item</button>
    </div>

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

    <ul id="items">
        <li ng-repeat="item in items"
            ng-include src="'ItemTemplate'"
            class="item-with-children">


            {{--<item--}}
                {{--object="item"--}}
                {{--items="items">--}}
            {{--</item>--}}

        </li>
    </ul>

    {{--<ul>--}}
    {{--@foreach($items as $item)--}}
        {{--@include('partials.item', $item)--}}

    {{--@endforeach--}}
    {{--</ul>--}}

</div>

<?php include($footer); ?>

@include('footer')

</body>
</html>