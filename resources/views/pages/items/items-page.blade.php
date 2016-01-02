<!DOCTYPE html>
<html lang="en" ng-app="lists">
<head>
    <meta charset="UTF-8">
    <title>Lists</title>
    @include('templates.shared.head-links')
</head>
<body ng-controller="ItemsController">

@include('templates.shared.header')
@include('templates.shared.loading')
<feedback-directive></feedback-directive>

@include('pages.items.popups.index')
@include('pages.items.pinned-items')

<div id="lists" class="container">
    @include('pages.items.breadcrumb')
    @include('pages.items.search')
    @include('pages.items.new-item')
    @include('pages.items.lists')

</div>

@include('templates.shared.footer')

</body>
</html>