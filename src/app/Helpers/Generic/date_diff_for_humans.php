<?php


use Carbon\Carbon;


if ( !function_exists( 'date_diff_for_humans' ) ) {
  /**
   * @param        $datetime
   * @param string $type long|short
   */
  function date_diff_for_humans ( $datetime, $type='short' ) {
  
    $carbon = new Carbon($datetime);
    
    switch ($type){
      case 'long':
        return $carbon->longRelativeDiffForHumans();
      case 'short':
        return $carbon->shortRelativeDiffForHumans();
      default:
        return $carbon->longRelativeDiffForHumans();
    }
  }
  
}
