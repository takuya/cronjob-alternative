@extends('home')

@section('title', config('app.name'))

@section('content_header')
  <div class="d-flex">
    <div class="">
      <h1 class="m-0 text-dark">Cronエントリ</h1>
    </div>
    <div class="position-relative" style="margin-left: auto">
      <button class="btn btn-outline-primary position-relative d-flex justify-content-center">
        <div class="mx-1"><i class="far fa-plus-square"></i></div>
        <div><a class="stretched-link" href="{{ route('user.cron.create') }}">新規追加</a></div>
      </button>
    </div>
  </div>
  <div class="float"> </div>

@stop

@section('plugins.Datatables', true)

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card d-none d-md-block">
        <div class="card-body">
          <div class="row align-items-md-center">
            <div class="col-1 d-none d-sm-block">id</div>
            <div class="col-md">name</div>
            <div class="col">cron</div>
            <div class="col d-none d-sm-block">shell</div>
            <div class="col">
              <div class="custom-control custom-switch">
                active
              </div>

            </div>
            <div class="col order-sm-12 order-4">
              info
            </div>
          </div>
        </div>
      </div>
      @foreach($entries as $idx=>$entry )
        <div class="card">
          <div class="card-body">
            <div class="row align-items-md-center">
              <div class="col-1 d-none d-sm-block">{{$entry->id}}</div>
              <div class="col-md card-title">{{$entry->name}}</div>
              <div class="col-auto d-sm-none">{{$entry->id}}</div>
              <div class="col">{{$entry->cron_date}}</div>
              <div class="col d-none d-sm-block">{{$entry->shell}}</div>
              <div class="col">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" {{$entry->enabled?'checked':''}}  disabled id="show_active">
                  <label class="custom-control-label" for="show_active"></label>
                </div>

                </div>
              <div class="col order-sm-12 order-4">
                <a class="btn btn-secondary" href="{{route('user.cron.show',[$entry->id])}}"><i
                      class="fas fa-info-circle"></i></a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@stop
