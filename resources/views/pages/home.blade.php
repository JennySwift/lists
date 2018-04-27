<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lists</title>
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    @include('templates.shared.head-links')
</head>
<body>

<div id="app">
    <navbar></navbar>
    {{--<toolbar></toolbar>--}}

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