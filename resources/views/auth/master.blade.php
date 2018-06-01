<!DOCTYPE html>
<html>
<head>
    @include('templates.shared.head')
    <link rel="stylesheet" href="/css/framework7.min.css">
</head>
<body>
<div id="app">
    <f7-statusbar></f7-statusbar>

    <f7-view id="main-view" main>
        @section('content')
        @show
    </f7-view>

</div>

<script type="text/javascript" src="{{ mix('/js/login.js') }}"></script>

</body>
</html>
