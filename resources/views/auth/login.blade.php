@extends('auth.master')

@section('content')

    <div id="login">
        <h1>Login</h1>

        <div id="requirements">
            <p>You can log in with the following email and password. This will give you access to an existing account with pre-populated data.</p>
            <ul>
                <li>Email: jennyswiftcreations@gmail.com</li>
                <li>Password: abcdefg</li>
            </ul>
        </div>

        <div id="requirements">
            <p>I suggest you use this app with either Mozilla Firefox or Google Chrome. You can try it in other browsers but it may not work as well.</p>
        </div>

        <div class="flex">
            @include('auth.errors')
            @include('auth.login-form')
        </div>
    </div>

@endsection
