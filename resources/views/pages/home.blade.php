<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Lists</title>
    @include('templates.shared.head-links')
</head>
<body>

<div id="app">
    <navbar></navbar>
    <toolbar></toolbar>

    <div class="main">
        <feedback></feedback>
        <loading></loading>

        <router-view></router-view>
    </div>

</div>

@include('templates.shared.footer')
@include('templates.shared.scripts')

</body>
</html>