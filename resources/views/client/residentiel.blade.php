@extends('layout')
@php
    use Illuminate\Support\Facades\DB;
    $clients = DB::table('clients')->select('first_name','last_name')->where('type','residentiel')->get();
    error_log($clients);
@endphp

@section('content')
    <div>
        <h3>Liste des clients residentiels</h3>
        <div class="form-box">
            <table>
                <tr>
                    <th>First name</th>
                    <th>Last name</th>
                </tr>
                @foreach($clients as $row)
                    <tr>
                        <td>{{$row->first_name}}</td>
                        <td>{{$row->last_name}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
