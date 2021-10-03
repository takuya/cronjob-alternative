<?php

namespace App\Models\Services;

use App\Models\CronLog;
use Symfony\Component\Process\Process;
use App\Models\CronEntry;
use App\Console\CronProcess\CronProcess;

class CronLogService {
  
  public static function find_proc( CronLog $log ){
  
    $gpid = posix_getpgid($log->pid);
    if ($gpid){
      $proc = Process::fromShellCommandline("ps -fje f");
      $proc->run();
      $out = $proc->getOutput();
      
      
      $lines = collect(\Str::of(trim($out))->trim()->split('/\n/'))
        ->filter(function($line) use($gpid){
        $cols =  \Str::of($line)->split('/\s+/');
        $id = $cols[3] ?? '';
        return $id == $gpid;
      })
      ->values();
      $pos = strpos($lines[0],'php');
      $pos = strrpos(substr($lines[0],0, $pos),' ');
      $lines = $lines->map(function($line) use ($pos){
        return substr($line,$pos);
      })->join(PHP_EOL);
      return $lines;
    }
    return '';
    
  }

  public static function findLastLogByCronEntry( CronEntry $entry): ?CronLog{
    $log = CronLog::where( 'name', $entry->name )
                  ->where('cron_entry','like',"%\"id\":{$entry->id}%")
                  ->orderBy('created_at','desc')
                  ->first();
    return $log;
  }
  public static function addLog ( CronProcess $executor ) {
    // これ、オーバーラップするとぶっ壊れますよね！しっかりして。
    
    $schedule_id = $executor->getScheduleId();
    $proc = $executor->getProcess();
    $log = CronLog::where( 'schedule_id', $schedule_id )->firstOrNew();
    $entry = $executor->getCronEntry();
    
    if ( !$log->id ) {
      $log->fill( [
        'schedule_id' => $schedule_id,
        'name' => $entry->name,
        'cron_entry' => $entry,
        'pid'=>$executor->getSubProcessId(),
      ] )->save();
    } else {
      $log->created_at = $log->created_at??time();
      $log->fill( [
        'name' => $entry->name,
        'schedule_id' => $schedule_id,
        'cron_entry' => $entry,
        'stdout' => $proc->getOutput(),
        'stderr' => $proc->getErrorOutput(),
        'exit_status_code' => $proc->getExitCode(),
        'operating_time' => $executor->getOperatingTime(),
        'pid'=>!$proc->isRunning()?null:$log->pid,
      ] )->save();
    }
  }
}