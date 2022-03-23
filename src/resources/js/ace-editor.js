

import ace from "ace-builds"
import 'ace-builds/webpack-resolver';

import 'ace-builds/src-noconflict/mode-javascript'
import 'ace-builds/src-noconflict/mode-php'
import 'ace-builds/src-noconflict/mode-sh'
import 'ace-builds/src-noconflict/ext-language_tools'

document.addEventListener('DOMContentLoaded', function () {
  
  const elName  = 'CrudRichTextEditor';
  if ( !document.querySelector("#"+elName)) {
    return false;
  }
  const hidden_text = document.querySelector('input[type=hidden][name="shell[body]"]');
  console.log(ace);
  const editor = ace.edit(elName);
  window.editor = editor;
  editor.getSession().setMode("ace/mode/javascript");
  editor.setTheme("ace/theme/chrome");
  editor.getSession().setUseWrapMode(true);
  editor.getSession().setTabSize(2);
  editor.getSession().setNewLineMode('unix')
  editor.setFontSize('1.2em');
  editor.setOptions({
    enableBasicAutocompletion: true
  });
  editor.getSession().on('change', function () {
    var _text_code = editor.getSession().getValue();
    hidden_text.value = _text_code;
  });

  const e = document.querySelector("div.show_code");
  if(!e){
    return false;
  }

  if (e.classList.contains('syntax_json')) {
    editor.getSession().setMode("ace/mode/json");
  }
  if (e.classList.contains('syntax_html')) {
    editor.getSession().setMode("ace/mode/html");
  }
  if (e.classList.contains('syntax_javascript')) {
    editor.getSession().setMode("ace/mode/javascript");
  }
  if (e.classList.contains('syntax_php')) {
    editor.getSession().setMode("ace/mode/php");
  }
  if (e.classList.contains('syntax_bash')) {
    editor.getSession().setMode("ace/mode/sh");
  }
  editor.setOptions({
    enableBasicAutocompletion: true,
    enableSnippets: true,
    enableLiveAutocompletion: true
  });
  
});
