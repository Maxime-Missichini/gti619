@extends('layout')
@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;

    $userId = Auth::id();
    $userEmail = DB::table('users')->select('email')->where('id', $userId)->first()->email;
@endphp
@section('content')
    <h3>Change password</h3>

    <div class="form-box" style="color: red">
        @error('old_password')
        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
        @enderror
        @error('password')
        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
        @enderror
        @error('password_confirmation')
        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
        @enderror
    </div>

    <div>
        <form class="form-box" action="{{ url('/password/change') }}" method="POST">
            @csrf
            <div>
                <label for="old_password">Old password</label>
                <input name="old_password" id="old_password" required placeholder="Old password" type="password"
                       class="form-control @error('old_password') is-invalid @enderror">
            </div>

            <div>
                <label for="password">New password</label>
                <input name="password" id="password" required placeholder="New password" type="password"
                       class="form-control @error('password') is-invalid @enderror">
            </div>
            <div>
                <label for="password_confirmation">Confirm new password</label>
                <input name="password_confirmation" required id="password_confirmation"
                       placeholder="Confirm new password"
                       type="password" class="form-control @error('password_confirmation') is-invalid @enderror">
            </div>

            <input type="hidden" name="email" value="{{ $userEmail }}">

            <input type="submit" value="Submit">
        </form>
    </div>
@endsection
