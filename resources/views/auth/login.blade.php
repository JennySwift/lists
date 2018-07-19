@extends('auth.master')

@section('content')
    <f7-page>
        <f7-navbar>
            <f7-nav-left></f7-nav-left>
            <f7-nav-title>Lists App Login</f7-nav-title>
            <f7-nav-right></f7-nav-right>
        </f7-navbar>
        <div id="login">

            <f7-block>
                To demo the app, log in with
                <ul>
                    <li>email:jennyswiftcreations@gmail.com</li>
                    <li>password: abcdefg</li>
                </ul>
                <p>Please be aware that for the demo, the data is public, so others may see changes you make, and vice versa.</p>
            </f7-block>
            
            <div class="flex">
                @include('auth.errors')

                <form class="form-horizontal" role="form" method="POST" action="/login">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">


                    <f7-list no-hairlines-md inset>
                        <f7-list-item>
                            <f7-label>Email</f7-label>
                            <f7-input type="email" name="email"  value="{{ old('email') }}" clear-button=""></f7-input>
                        </f7-list-item>

                        <f7-list-item>
                            <f7-label>Password</f7-label>
                            <f7-input type="password" name="password" clear-button=""></f7-input>
                        </f7-list-item>

                        {{--<f7-list-item>--}}
                        {{--<f7-label>Remember Me</f7-label>--}}
                        {{--<f7-toggle name="remember"></f7-toggle>--}}
                        {{--</f7-list-item>--}}
                        <f7-list-item title="Remember Me">
                            <div class="item-after">
                                <label class="toggle toggle-init">
                                    <input type="checkbox" name="toggle" value="yes"><i class="toggle-icon"></i>
                                </label>
                            </div>
                        </f7-list-item>

                    </f7-list>

                    <f7-block>
                        <input type="submit" class="button" value="Login">
                    </f7-block>


                </form>
            </div>
        </div>

    </f7-page>

@endsection
