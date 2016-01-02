<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lists</title>
    @include('templates.shared.head-links')
</head>
<body>

@include('templates.shared.header')

@include('pages.items.popups.index')
@include('pages.items.pinned-items')

<items></items>

@include('templates.shared.footer')

</body>
</html>