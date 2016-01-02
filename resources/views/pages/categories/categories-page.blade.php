<!DOCTYPE html>
<html lang="en" ng-app="lists">
<head>
    <meta charset="UTF-8">
    <title>Lists</title>
    @include('templates.shared.head-links');

</head>
<body>

@include('templates.shared.header')

<div ng-controller="CategoriesController" id="categories" class="container">

    <feedback-directive></feedback-directive>

    <h1>categories</h1>

    <label>Create a new category</label>
    <input
        ng-keyup="createCategory($event.keyCode)"
        type="text"
        placeholder="new category"
        id="new-category"/>

    <button ng-click="createCategory(13)" class="btn btn-success">Create</button>

    <ul>
        <li ng-repeat="category in categories">[[category.name]]</li>
    </ul>

</div>

@include('templates.shared.footer')

</body>
</html>