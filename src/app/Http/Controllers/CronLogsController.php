<?php

namespace App\Http\Controllers;

use RuntimeException;
use App\Models\CronLog;
use App\Models\CronEntry;

class CronLogsController extends Controller {
  
  public function index() {
    
    $entries = CronLog::orderBy('updated_at', 'desc')->paginate(100);
    
    //$headers = ['id','name','exit_status_code','created_at'];
    return view('cron_logs.index', ['entries' => $entries]);
  }
  
  public function show_with_entry( CronEntry $cron, CronLog $log ) {
    
    return $this->show($log);
  }
  
  public function show( CronLog $log ) {
    
    return view('cron_logs.show', ['entry' => $log]);
  }
  
  public function killJob( CronLog $log, string $pid ) {
    
    
    if( $pid == $log->pid ) {
      $ret = posix_kill($log->pid, SIGHUP);
      
      return ['success' => true];
    } else {
      throw new RuntimeException("process not found");
    }
  }
}
