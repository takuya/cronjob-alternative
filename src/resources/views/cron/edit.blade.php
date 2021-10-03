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
        'formMode' => 'edit',
        'action' => route('user.cron.update',[$entry->id])
      ])
    </div>
  </div>
@stop
