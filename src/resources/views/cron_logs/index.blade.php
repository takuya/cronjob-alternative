@extends('home')

@section('title', config('app.name'))

@section('content_header')
  <h1 class="m-0 text-dark">実行ログ</h1>
@stop

@section('plugins.Datatables', true)

@section('content')
  @if ( request()->query('cron_entry_id') )
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          Filter
        </div>
        <div class="card-body">
            <a href="{{ route('user.logs.index') }}" class="btn btn-outline-primary text-decoration-none">
              <i class='hover text-black fas fa-1x fa-window-close'></i>
              Cron={{request()->query('cron_entry_id')}}</a>
        </div>
      </div>
    </div>
  </div>
  @endif

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          Logs
        </div>
        <div class="card-body">
          <table class="table table-striped table-sm">
            <thead>
            <tr>
              <th>名前</th>
{{--              <th>実行ID</th>--}}
              <th>開始時間</th>
              <th>ステータス</th>
              <th>action</th>
            </tr>
            </thead>
            <tbody>

            <?php
            use App\Models\CronLog;
            /** @var CronLog $entry */
            /** @var CronLog[] $entries */
            ?>
            @foreach($entries as $entry)
              <tr>
                <td>{{ $entry->name}}</td>
{{--                <td>{{ $entry->schedule_id}}</td>--}}
                <td>{{date_diff_for_humans_jp($entry->created_at)}}</td>
                <td>@if($entry->isError())
                      <i class=" text-warning fas fa-exclamation-circle"></i>
                    @elseif($entry->isFinished())
                      <i class="text-success fas fa-check-circle"></i>
                    @elseif($entry->isRunning())
                      @if($entry->isWaiting())
                        <i class="far fa-pause-circle"></i>
                      @else
                        <i class="fas fa-sync-alt faa-spin animated faa-slow"></i>
                      @endif
                    @endif
                  {{ $entry->getStatus() }}</td>
                <td>
                  <div class="btn btn-default position-relative">

                    <a class="stretched-link" href="{{ route('user.log.show', [$entry->id]) }}">詳細</a>

                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
          {{$entries->links('pagination::bootstrap-4')}}

        </div>
      </div>
    </div>
  </div>
@stop
