@extends('layout')
@php
use Spatie\Valuestore\Valuestore;
$valuestore=Valuestore::make('settings.json');
@endphp
@section('content')
<div>
    <h3>Administrator panel</h3>
    <div class="box"><a href="{{ url('/home') }}"><p>Home</p></a></div>
    <div>Parameters</div>
    <form class="form-box" method="POST" action="{{ url('/config') }}">
        @csrf
        <div>
            <label for="try">Maximum number of authentication try:</label>
            <input name="try" value="{{ $valuestore->get('password_max_try', 3) }}" required>
        </div>
        <div>
            <label for="delay">Delay between attempts (min):</label>
            <input name= "delay" value="{{ $valuestore->get('password_attempt_delay', 1) }}" required>
        </div>
        <div>
            <label for="reset">Password change (true, false):</label>
            <input name= "reset" value="{{ $valuestore->get('password_reset', 'true')}}" required>
        </div>
        <div>
            <label for="length">Password minimum length:</label>
            <input name= "length" value="{{ $valuestore->get('password_minimum_length', 8) }}" required>
        </div>
        <div>
            <label for="allowed">Password required characters (all, mixed, alphanumeric):</label>
            <input name= "allowed" value="{{ $valuestore->get('password_characters_allowed', 'all')}}" required>
        </div>
        <div>
            <label for="reusable">Lasts passwords not reusable:</label>
            <input name= "reusable" value="{{ $valuestore->get('password_reusable', 1) }}" required>
        </div>
        <div>
            <input type="submit" value="Submit">
        </div>
    </form>
</div>
@endsection
