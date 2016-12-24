<!doctype html>

<html lang="en" class="">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Lists App</title>
    {{--<link rel="stylesheet" href="../tools/bootstrap.min.css">--}}
    {{--Bootstrap is the only one I need from plugins.css--}}
    @include('templates.shared.head-links')
</head>

<body>
    <div class="main">
        @section('content')
        @show
    </div>

    @include('templates.shared.footer')

</body>

