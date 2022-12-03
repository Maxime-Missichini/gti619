@extends('layout')

@section('content')
    <div>
        @auth
            <h3>Welcome {{ Auth::user() -> name }}</h3>
        @else
            <h3>Welcome</h3>
        @endauth
        @if(Route::has('login'))
            <div>
                @auth
                    <div class="box"><a href="{{ url('/home') }}"><p>Home</p></a></div>
                    <div class="box">
                        <a href="{{ url('/logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit()"><p>Logout</p></a>
                    </div>
                    <div class="box"><a href="{{ url('/password/change') }}"><p>Change password</p></a></div>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                        @csrf
                    </form>
                @else
                    <div class="box"><a href="{{ url('/login') }}"><p>Login</p></a></div>
                    @if(Route::has('register'))
                        <div class="box"><a href="{{ url('/register') }}"><p>Register</p></a></div>
                    @endif
                @endauth
            </div>
        @endif
            @auth
                @can("see-admin")
                    <div class="box">
                        <a href="{{ url('/admin') }}"><p>Admin panel</p></a>
                    </div>
                @endcan
                @can("see-residentiel")
                        <div class="box">
                            <a href="{{ url('/residentiel') }}"><p>Clients residentiels</p></a>
                        </div>
                    @endcan
                    @can("see-affaire")
                        <div class="box">
                            <a href="{{ url('/affaire') }}"><p>Clients d'affaires</p></a>
                        </div>
                    @endcan
            @endauth
    </div>
@endsection
