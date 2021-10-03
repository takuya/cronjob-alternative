@extends('adminlte::page')

@section('title', config('app.name'))

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop
@section('js')
    <script src="{{asset('js/app.js')}}" ></script>
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stop


@section('content')
    <div id="app"></div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">You are logged in!</p>
                </div>
            </div>
        </div>
    </div>
@stop
