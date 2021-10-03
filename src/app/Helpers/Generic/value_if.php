<?php


if ( !function_exists( 'value_if' ) ) {
  function value_if ( $cond, $value ) {
    if ( is_callable($cond)){
      $cond = $cond();
    }
    
    return $cond ? $value : null;
  }
}
