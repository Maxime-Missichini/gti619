@extends('layout')

@section('content')
    <div>
        @auth
            <div>Welcome {{ Auth::user() -> name }}</div>
        @else
            <div>Welcome</div>
        @endauth
        @if(Route::has('login'))
            <div>
                @auth
                    <div><a href="{{ url('/home') }}">Home</a></div>
                    <div>
                        <a href="{{ url('/logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit()">Logout</a>
                    </div>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                        @csrf
                    </form>
                @else
                    <div><a href="{{ url('/login') }}">Login</a></div>
                    @if(Route::has('register'))
                        <div><a href="{{ url('/register') }}">Register</a></div>
                    @endif
                @endauth
            </div>
        @endif
    </div>
@endsection
