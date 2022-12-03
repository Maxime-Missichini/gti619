@extends('layout')

@section('content')
    <h3>{{ __('Register') }}</h3>

    <div class="form-box" style="color: red">
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
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
    </div>

    <div>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="box">
                <label for="name">{{ __('Name') }}</label>

                <div>
                    <input id="name" type="text"
                           class="form-control @error('name') is-invalid @enderror" name="name"
                           value="{{ old('name') }}" required autocomplete="name" autofocus>
                </div>
            </div>

            <div class="box">
                <label for="email">{{ __('Email Address') }}</label>

                <div>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror" name="email"
                           value="{{ old('email') }}" required autocomplete="email">
                </div>
            </div>

            <div class="box">
                <label for="password">{{ __('Password') }}</label>

                <div>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror" name="password"
                           required autocomplete="new-password">
                </div>
            </div>

            <div class="box">
                <label for="password-confirm">{{ __('Confirm Password') }}</label>

                <div>
                    <input id="password-confirm" type="password" class="form-control"
                           name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>

            <div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        {{ __('Register') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
