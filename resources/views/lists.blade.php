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
    @include('templates.lists.popups.index')
    @include('templates/feedback')
    @include('templates/lists/search')
    @include('templates/lists/new-item')
    @include('templates/lists/breadcrumb')
    @include('templates/lists/lists')

</div>

<?php include($footer); ?>

@include('footer')

</body>
</html>