@extends('layout')
@php
    use App\Models\User;
@endphp
@section('content')
    <h3>{{ __('Login') }}</h3>

    <div class="form-box" style="color: red">
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        @error('challenge')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="box">
                <label for="email">{{ __('Email Address') }}</label>

                <div>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror" name="email"
                           value="{{ old('email') }}" required autocomplete="email" autofocus>
                </div>
            </div>

            <div class="box">
                <label for="password">{{ __('Password') }}</label>

                <div>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror" name="password"
                           required autocomplete="current-password">
                </div>
            </div>

            <div class="form-box">
                <label class="challenge">{{ __('Challenge') }}</label>
                @php
                    $size = User::$gridSize;
                    $challengeSize = User::$challengeSize;
                    $challenge = [];
                    for($i = 0; $i < $challengeSize; $i++){
                        $res = 0;
                        $res = random_int(1, $size);
                        //Recherche de dupliqués
                        while(in_array($res, $challenge)){
                            $res = random_int(1, $size);
                        }
                        $challenge[$i] = $res;
                    }
                    $stringChallenge = collect($challenge)->implode(';');
                @endphp
                @foreach($challenge as $pos)
                    <h2 style="color: white">{{ $pos }}</h2>
                @endforeach
            </div>

            <input type="hidden" name="question" value="{{ $stringChallenge }}">

            <div class="box">
                <label for="challenge">{{ __('Challenge response') }}</label>
                <div>
                    <input id="challenge" type="text"
                           class="form-control @error('challenge') is-invalid @enderror" name="challenge"
                           required maxlength="3">
                </div>
            </div>

            <div>
                <input type="checkbox" name="remember"
                       id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label style="color: black" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>

            <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
