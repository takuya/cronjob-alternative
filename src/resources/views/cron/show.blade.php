@extends('home')

@section('title', config('app.name'))

@section('content_header')
  <h1 class="m-0 text-dark">Cronジョブ定義エントリ</h1>
@stop

@section('plugins.Datatables', true)

@section('content')
  @php
    use App\Models\CronEntry;
    /** @var CronEntry $entry */
    $entry = $entry;
  @endphp


  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex ">
          <div class="card-title mx-auto ">
            <div class="justify-content-center">
              {{$entry->name}}
            </div>
          </div>
          <div class="btn-group">
            <div type="button" class="btn">
              <a href="{{ route('user.cron.edit',[$entry->id]) }}" class="stretched-link"><i
                    class="far fa-edit"></i></a>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <table class="table table-hover table-sm">
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
              <th rowspan="2" style="vertical-align: middle">cron時刻</th>
              <td>{{$entry->cron_date}}</td>
            </tr>
            <tr>
              <td>{{cron_expression_for_human($entry->cron_date)}}</td>
            </tr>
            <tr>
              <th>シェル</th>
              <td>{{$entry->shell}}</td>
            </tr>
            <tr>
              <th>Working Directory</th>
              <td>{{$entry->cwd??'未指定'}}</td>
            </tr>
            <tr>
              <th>Environment</th>
              <td>{{$entry->env? json_pp($entry->env) :'未指定'}}</td>
            </tr>
            <tr>
              <th>ユーザ(sudo -u)</th>
              <td>{{$entry->user??'未指定'}}</td>
            </tr>
            <tr>
              <th>待機（最大）</th>
              <td>{{$entry->random_wait?$entry->random_wait.'秒':'未指定'}}</td>
            </tr>
            <tr>
              <th>ID</th>
              <td>{{$entry->id}}</td>
            </tr>
            <tr>
              <th>メモ</th>
              <td>{{$entry->comment}}</td>
            </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          コマンド
        </div>
        <div class="card-body p-0">
          <div class="pre-wrap console w-100">{{$entry->command??''}}</div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">次回実行</div>
        <div class="card-body">
          <div id="app_pause">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="pause_cron"
                     v-model="enabled"
              >
              <label class="custom-control-label" for="pause_cron">
                <span
                    v-bind:class='{ "text-muted text-black-50 text-del":  !enabled_status }'>{{$entry->getNextDueDate()}}</span>
                <transition>
                  <span v-show="message"><i class="fas fa-info-circle"></i>updated</span>
                </transition>
              </label>
            </div>
          </div>
          <script defer>
            document.addEventListener('DOMContentLoaded', function () {
              const app = new Vue({
                el: '#app_pause',
                mounted: function () {
                },
                methods: {},
                data: {
                  message: false,
                  enabled: {{$entry->enabled?'true':'false'}},
                  enabled_status: {{$entry->enabled?'true':'false'}},
                },
                watch: {
                  enabled: function (_new, _old) {
                    $.ajaxSetup({
                      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                    });
                    $.ajax({
                      url: "{{ route('user.cron.pause', [$entry->id]) }}",
                      type: 'POST',
                      data: {enabled: _new},
                      success: (data) => {
                        this.enabled_status = this.enabled;
                        this.message = true;
                        window.setTimeout(() => this.message = false, 1000);
                      },
                      error: function () {
                      }
                    });
                  }
                }
              });
            });

          </script>

        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div data-toggle="collapse" href="#runNowCollapse" role="button" aria-expanded="false">
            今すぐ実行
          </div>
        </div>
        <div class="collapse" id="runNowCollapse">
          <div class="card-body">
            <div class="row justify-content-center">
              <div class="col-12 col-md-auto">
                <!-- Button trigger modal -->
                <div class="w-100 btn btn-outline-secondary" data-toggle="modal" data-target="#run_now_dialog">
                  Run
                </div>
                <script defer>
                  document.addEventListener('DOMContentLoaded', function () {
                    document.querySelector('#run_now').addEventListener('click', function () {
                      $("#run_now_dialog").modal('hide')
                      $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                      });
                      $.ajax({
                        url: "{{ route('user.cron.run_now', [$entry->id]) }}",
                        type: 'POST',
                        data: {run_now: true},
                        success: (data) => {
                          window.setTimeout(() => window.location.reload(), 1000);
                        },
                        error: function () {
                          window.setTimeout(() => window.location.reload(), 1000);
                        }
                      });
                    })


                  });

                </script>

                <!-- Modal -->
                <div class="modal fade" id="run_now_dialog" tabindex="-1" aria-labelledby="alertRunAsModal"
                     aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="alertRunAsModal">権限について</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        今すぐ実行します？<br>
                        (今すぐ実行はキューを使います。ワーカーが起動している必要があります。）
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">やめる</button>
                        <button type="button" id="run_now" class="btn btn-primary">今すぐ実行する</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">最終実行</div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            @foreach($last_log as $log)
              @php
                /** @var \App\Models\CronLog $log */
              @endphp
              <li class="list-group-item hover-row">
                <div class="row">
                  <div class="col">{{ date_diff_for_humans_jp($log->created_at) }}<br>
                    ({{ $log->created_at }})
                  </div>
                  <div class="col ">
                    @if($log->isError())
                      <i class=" text-warning fas fa-exclamation-circle"></i>
                    @elseif($log->isFinished())
                      <i class="text-success fas fa-check-circle"></i>
                    @elseif($log->isRunning())
                      @if($log->isWaiting())
                        <i class="far fa-pause-circle"></i>
                      @else
                        <i class="fas fa-sync-alt faa-spin animated faa-slow"></i>
                      @endif
                    @endif
                    {{ $log->getStatus() }}</div>
                  <div class="col"><a
                        href="{{ route('user.cron.logs.show', ['cron'=>$entry->id,'log'=>$log->id]) }}">詳細</a></div>
                </div>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="card">
        <div class="card-header position-relative">
          <div data-toggle="collapse" href="#removeCollapse" role="button" class="">remove</div>
        </div>
        <div class="collapse" id="removeCollapse">
          <div class="card-body">
            <button class="btn btn-danger" data-toggle="modal" data-target="#removeFormModal">削除</button>
          </div>
        </div>
        <div class="modal fade" id="removeFormModal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Are you sure ? </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>this will be removed.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="removeOK">REMOVE</button>
              </div>
              <form action="{{route('user.cron.destroy',$entry->id)}}" method="post" id="FormRemove">
                @csrf
                @method('DELETE')
                <script defer>
                  document.addEventListener('DOMContentLoaded', function () {
                    $("#removeOK").click(function () {
                      $("#FormRemove").submit()
                    });
                  });
                </script>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop
