@extends('home')

@section('title', config('app.name'))

@section('content_header')
  <h1 class="m-0 text-dark">Artisan schedule:list</h1>
@stop


@section('content')

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          artisan schedule:list
        </div>
        <div class="card-body">
          <table class="table table-striped table-responsive">
            <thead>
            <tr>
              <th>名前</th>
              <th>次回</th>
              {{--              <th>実行</th>--}}
              <th>前回</th>
              <th>Cron</th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($entries as $entry)
              <tr>
                <td>{{ $entry['desc']}}</td>
                <td>{{ $entry['next']}}</td>
                {{--                <td>{{ $entry['artisan']}}</td>--}}
                <td>{{ $entry['last']}}</td>
                <td>{{ cron_expression_for_human( $entry['cron'] )}}</td>
                <td>
                  @if($entry['cron_entry_id'])
                    <button class="btn btn-outline-primary position-relative">
                    <a class="stretched-link text-decoration-none " href=" {{ route('user.cron.show', [$entry['cron_entry_id']]) }}">詳細</a>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@stop