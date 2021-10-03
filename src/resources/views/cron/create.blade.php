@extends('home')

@section('title', config('app.name'))

@section('content_header')
  <h1 class="m-0 text-dark">Cronジョブ定義編集</h1>
@stop

@section('plugins.Datatables', true)

@section('content')
  <div class="row">
    <div class="col-12">
      @include ('cron.form', [
        'formMode' => 'create',
        'action' => route('user.cron.store')
      ])
    </div>
  </div>
@stop

