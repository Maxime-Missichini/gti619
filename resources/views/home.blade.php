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
            @auth
                @can("see-admin")
                    <div>
                        <a href="{{ url('/admin') }}">Admin panel</a>
                    </div>
                @endcan
                @can("see-residentiel")
                        <div>
                            <a href="{{ url('/residentiel') }}">Clients residentiels</a>
                        </div>
                    @endcan
                    @can("see-affaire")
                        <div>
                            <a href="{{ url('/affaire') }}">Clients d'affaires</a>
                        </div>
                    @endcan
            @endauth
    </div>
@endsection
