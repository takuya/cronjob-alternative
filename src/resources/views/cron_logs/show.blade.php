@extends('home')

@section('title', config('app.name'))

@section('content_header')
  <h1 class="m-0 text-dark">スケジュール・ジョブ情報</h1>
@stop

@section('plugins.Datatables', true)

@section('content')
  @php
    use App\Models\CronLog;
    /** @var CronLog $entry */
  @endphp
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">プロセス実行情報</div>
        <div class="card-body">
          <table class="table table-sm">
            <thead>
            <th>項目</th>
            <th>内容</th>
            </thead>
            <tbody>
            <tr>
              <th>名前</th>
              <td>{{$entry->name}}</td>
            </tr>
            <tr>
              <th>実行日</th>
              <td>{{$entry->created_at}}</td>
            </tr>
            <tr>
              <th>実行ID</th>
              <td>{{$entry->schedule_id}}</td>
            </tr>
            <tr>
              <th>Exitコード</th>
              <td>

                @if($entry->isError())
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
                {{ $entry->getStatus() }}
                @if($entry->isFinished()) ( exit code = {{$entry->exit_status_code}})@endif
                @if($entry->isRunning()) ( pid = {{$entry->pid}})@endif


                @if($entry->isRunning())
                  <script>
                    // 実行中はオートリロード
                    document.addEventListener('DOMContentLoaded',()=>{
                      window.reload_timer = window.setTimeout(()=>{ window.location.reload(); }, 5000)
                    });
                  </script>
                @endif
                </td>
            </tr>
            <tr>
              <th>実行時間</th>
              <td>
                {{date_interval_for_humans_jp($entry->operating_time??0)}}<br>
                ({{ number_format($entry->operating_time, 3) }}秒)</td>
            </tr>
            <tr>
              <th>ジョブ定義</th>
              <td>
                @if($entry->getCronEntry())
                  <a href="{{ route('user.cron.show',[$entry->cron_entry['id']]) }}">詳細</a>
                @else
                @endif
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      @if( $entry->isRunning() && $entry->pid)
        <div class="card">
          <div class="card-header">プロセス操作</div>
          <div class="card-body">
            <div class="list-group-flush">
              <div class="list-group-item">
                  <div class="console pre-wrap" >{{ \App\Models\Services\CronLogService::find_proc($entry)  }}</div>
              </div>
              <div class="list-group-item">
                <div class="row">
                  <div class="col">プロセス番号</div>
                  <div class="col"> {{$entry->pid}}</div>
                  <div class="col">
                    <script>
                      function kill_process() {
                        if (window.confirm('kill this?')) {
                          var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                          $.ajax({
                            url: "{{ route('user.cron.job.kill', [$entry->id,$entry->pid]) }}",
                            type: 'DELETE',
                            data: {_token: CSRF_TOKEN},
                            success: function (data) {
                              alert('Process Killed.');
                              window.location.reload()
                            },
                            error: function () {
                            }
                          });

                        }
                      }
                    </script>
                    <div class="btn btn-danger">
                      <a class="text-white" href="#kill_proc" onclick="kill_process();void(0);">KILL</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif

      <div class="card">
        <div class="card-header">標準出力</div>
        <div class="card-body">
          @if($entry->stdout)
            <div class="console pre-wrap">{{$entry->stdout}}</div>
          @endif
        </div>
      </div>
      <div class="card">
        <div class="card-header">標準エラー出力</div>
        <div class="card-body">
          @if($entry->stderr)
            <div class="console pre-wrap">{{$entry->stderr}}</div>
          @endif
        </div>
      </div>
    </div>
  </div>
@stop
