<?php


use Carbon\Carbon;


if ( !function_exists( 'date_diff_for_humans_jp' ) ) {
  /**
   * @param        $datetime
   */
  function date_diff_for_humans_jp($datetime){
    
    $diff_str = date_diff_for_humans($datetime,'short');
    
    return Str::of($diff_str)
      ->replaceMatches('/ from now/', '後')
      ->replaceMatches('/ ago/', '前')
      ->replaceMatches('/(\d{1,2})mos?/', '$1ヶ月')
      ->replaceMatches('/(\d{1,2})yrs?/', '$1年')
      ->replaceMatches('/(\d{1,2})s/', '$1秒')
      ->replaceMatches('/(\d{1,2})m/', '$1分')
      ->replaceMatches('/(\d{1,2})h/', '$1時間')
      ->replaceMatches('/(\d{1,2})d/', '$1日')
      ->replaceMatches('/(\d{1,2})w/', '$1週間')
      
      ;
    
  }
}
