<?php



function json_pp($value){
  
  $flags = JSON_UNESCAPED_UNICODE|
    JSON_UNESCAPED_SLASHES|
    JSON_PRETTY_PRINT|
    JSON_UNESCAPED_LINE_TERMINATORS;
  return json_encode($value,$flags);
}