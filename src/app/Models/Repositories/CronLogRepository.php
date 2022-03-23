<?php

namespace App\Models\Repositories;

use App\Models\CronLog;

class CronLogRepository {
  
  public static function OrphanLog(){
    $list = CronLog::all();
    $list = $list->filter(function( CronLog  $e){
      $p = $e->getCronEntry();
      return is_null($p);
    });
    return $list;
  }
  
}