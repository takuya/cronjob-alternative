@php
  use App\Models\CronEntry;
   /** @var CronEntry $entry */
   $entry = $entry;
   /** @var Illuminate\Support\ViewErrorBag  $errors */
   $errors = $errors
@endphp

<div id="app_edit" class="form-group" v-shortkey="['ctrl', 's']" @shortkey="ignoreSave()">
  <form action="{{ $action }}" method="post">
    <div class="card">
      <div class="card-header">必須情報</div>
      <div class="card-body">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" >name</span>
          </div>
          <x-forms.input-text
              name="name"
              value='{{$entry->name}}'
              placeholder="name of cron job"
          ></x-forms.input-text>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" >cron expression</span>
          </div>
          <x-forms.input-text
              name="cron_date"
              value='{{$entry->cron_date}}'
              placeholder="cron expression"
          ></x-forms.input-text>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">コマンド</div>
      <div class="card-body">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" >shell</span>
          </div>
          <select name="shell[cmd]"
                  id="shell_select"
                  class="custom-select {{ value_if($errors->has('shell'),'is-invalid')}}"
                  v-model="shell"
          >
            @foreach( supported_shell() as $shell )
              <option value="{{$shell}}">{{$shell}}</option>
            @endforeach
          </select>
          @error('shell')
          <div class="invalid-feedback">{{ join('',$errors->get('shell')) }}</div>
          @enderror
        </div>

        <div class="show_code syntax_{{old('shell.cmd', $entry->shell??'bash')}}">
          <div id="CrudRichTextEditor" style="height: 20vh;">{{old('shell.body', $entry->command??'echo Hello;')}}</div>
        </div>
        <input type="hidden" name="shell[body]" value="{{old('shell.body', $entry->command??'echo Hello;')}}">
      </div>
    </div>
    <div class="card">
      <div class="card-header">オプション</div>
      <div class="card-body">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" >working directory</span>
          </div>
          <x-forms.input-text
              name="cwd"
              value='{{$entry->cwd}}'
              placeholder=""
          ></x-forms.input-text>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" >env</span>
          </div>
          <x-forms.input-text
              name="env"
              :value='$entry->env?json_encode($entry->env):""'
              placeholder=""
          ></x-forms.input-text>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" >user</span>
          </div>
          <x-forms.input-text
              name="user"
              value='{{$entry->user}}'
              placeholder=""
          ></x-forms.input-text>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" >wait</span>
          </div><x-forms.input-text
              name="random_wait"
              value='{{$entry->random_wait}}'
              placeholder=""
          ></x-forms.input-text>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">メモ</div>
      <div class="card-body">
        <textarea name="comment" class="form-control">{{$entry->comment}}</textarea>
      </div>
    </div>
    <div class="card">
      <div class="card-body d-flex justify-content-center">
        <button class="btn flex-fill btn-primary">
          Save
        </button>
      </div>
    </div>
    @method( $formMode == 'edit'?'PUT':'POST')
    @csrf
  </form>
</div>
<script defer="defer">
  document.addEventListener('DOMContentLoaded', function () {

    const app = new Vue({
      el: '#app_edit',
      data: {
        shell: "{{ old('shell.cmd', $entry->shell??'bash') }}"
      },
      methods: {
        ignoreSave() {},
        update_editor_mode(name){
          console.log(name)

          modeList = {
            'bash': "ace/mode/sh",
            'php': "ace/mode/php",
          }
          mode = modeList[name];
          window.editor.getSession().setMode(mode);
        },
      },
      watch: {
        "shell": function(_new, _old) {
          this.update_editor_mode(_new)
        }
      }
    });
  });
</script>
