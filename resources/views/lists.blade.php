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

    <h1>Lists</h1>

    <div id="breadcrumb">
        <div ng-repeat="item in breadcrumb">
            <span ng-click="zoom(item)">[[item.title]]</span>
            <i ng-if="!$last" class="fa fa-angle-right"></i>
        </div>
    </div>

    <ul>
        <li ng-repeat="item in items" ng-include src="'ItemTemplate'">


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