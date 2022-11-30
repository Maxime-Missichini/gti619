@extends('layout')
@php
use Spatie\Valuestore\Valuestore;
$valuestore=Valuestore::make('settings.json');
@endphp
@section('content')
<div>
    <h3>Administrator panel</h3>
    <div><a href="{{ url('/home') }}">Home</a></div>
    <div>Parameters</div>
    <form method="POST" action="{{ url('/config') }}">
        @csrf
        <div>
            <label for="try">Maximum number of authentication try:</label>
            <input name= "try" value="{{ $valuestore->get('password_max_try', 3) }}" required>
        </div>
        <div>
            <label for="delay">Delay between attempts:</label>
            <input name= "delay" value="{{ $valuestore->get('password_attempt_delay', 60) }}" required>
        </div>
        <div>
            <label for="reset">Password change:</label>
            <input name= "reset" value="{{ $valuestore->get('password_reset', 'yes')}}" required>
        </div>
        <div>
            <label for="length">Password minimum length:</label>
            <input name= "length" value="{{ $valuestore->get('password_minimum_length', 8) }}" required>
        </div>
        <div>
            <label for="allowed">Password allowed characters:</label>
            <input name= "allowed" value="{{ $valuestore->get('password_characters_allowed', 'all')}}" required>
        </div>
        <div>
            <label for="reusable">Last password reusable:</label>
            <input name= "reusable" value="{{ $valuestore->get('password_reusable', 1) }}" required>
        </div>
        <div>
            <input type="submit" value="Enter">
        </div>
    </form>
</div>
@endsection
