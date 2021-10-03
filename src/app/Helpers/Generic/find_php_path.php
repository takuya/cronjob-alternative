<?php


if (! function_exists('find_php_path')){
  
  function find_php_path(){
  
    $php = 'php'.PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;
    $php = shell_exec("which {$php} 2> /dev/null")
      ?? shell_exec("which php 2> /dev/null")
      ?? NULL;
    $php = trim($php);
    return $php;
  }
}