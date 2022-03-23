<?php

namespace App\Http\Controllers;

use RuntimeException;
use App\Models\CronLog;
use App\Models\CronEntry;
use Illuminate\Http\Request;

class CronLogsController extends Controller {
  
  public function index( Request $req) {
    $cron_entry_id = $req->query('cron_entry_id');
    if ($cron_entry_id){
      $entries = CronEntry::findOrFail($cron_entry_id)->logs()->paginate(100);
    }else{
      $entries = CronLog::orderBy('updated_at', 'desc')->paginate(100);
    }
    
    
    return view('cron_logs.index', ['entries' => $entries->appends($req->input())]);
  }
  
  public function show_with_entry( CronEntry $cron, CronLog $log ) {
    
    return $this->show($log);
  }
  
  public function show( CronLog $log ) {
    
    return view('cron_logs.show', ['entry' => $log]);
  }
  
  public function killJob( CronLog $log, string $pid ) {
    
    
    if( $pid == $log->pid ) {
      if (defined('SIGHUP')){
        define( 'SIGHUP', 1);
      }
      $ret = posix_kill($log->pid, SIGHUP);
      
      return ['success' => true];
    } else {
      throw new RuntimeException("process not found");
    }
  }
}
