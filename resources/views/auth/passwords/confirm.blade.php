@extends('layout')

@section('content')
    <h3>{{ __('Confirm Password') }}</h3>

    <div class="form-box" style="color: red">
        @error('password')
        <span class="invalid-feedback" role="alert">
                                        <strong>{{ 'Wrong password' }}</strong>
                                    </span>
        @enderror
    </div>

    <div>{{ __('Please confirm your password before continuing.') }}</div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-box">
            <label for="password"> {{ __('Password') }} </label>

            <div>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror" name="password"
                       required autocomplete="current-password">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">
                    {{ __('Confirm Password') }}
                </button>
            </div>
        </div>

        <div>
            <div>
                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </div>
    </form>
@endsection
