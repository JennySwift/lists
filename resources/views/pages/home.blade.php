<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Lists</title>
    @include('templates.shared.head-links')
</head>
<body>

<navbar></navbar>

<feedback></feedback>
<loading></loading>

<div class="main">
    <router-view></router-view>
</div>

@include('templates.shared.footer')

</body>
</html>