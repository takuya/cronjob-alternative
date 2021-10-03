@extends('home')

@section('title', config('app.name'))

@section('content_header')
  <h1 class="m-0 text-dark">Systemd Jouranld</h1>
@stop


@section('content')

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          {{$cmd}}
        </div>
        <div class="card-body">
            <div class="console pre-wrap" style="max-height:50vh;overflow: auto">{{$out}}</div>
        </div>
      </div>
    </div>
  </div>
@stop