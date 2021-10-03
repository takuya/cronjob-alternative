<?php


use App\Services\SyntaxCheck\SyntaxCheckService;

function supported_shell(){
  return array_keys(SyntaxCheckService::supportedList());
}