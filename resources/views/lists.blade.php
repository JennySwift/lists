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

</div>

<?php include($footer); ?>

@include('footer')

</body>
</html>