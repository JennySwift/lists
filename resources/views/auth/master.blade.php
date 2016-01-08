<!doctype html>

<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <title>Lists App</title>
    {{--<link rel="stylesheet" href="../tools/bootstrap.min.css">--}}
    {{--Bootstrap is the only one I need from plugins.css--}}
    @include('templates.shared.head-links')
    <style>
        #navbar {display: flex;}
        body footer {display: flex;}
        body .main {display: block;}
    </style>
</head>

<body>
    @include('templates.shared.header')
    <div class="main">
        @section('content')
        @show
    </div>

    @include('templates.shared.footer')

</body>

