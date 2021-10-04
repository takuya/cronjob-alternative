<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CronLog;

class CronJobKillJob implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  
  /** @var CronLog */
  protected $cronLog_entry;
  
  public function __construct ( CronLog $entry ) {
    $this->cronLog_entry = $entry;
  }
  public function handle ():void {
    if(!$this->cronLog_entry->isRunning()){
      return;
    }
  
    $pid = $this->cronLog_entry->pid;
    posix_kill($pid, SIGHUP);
  }
}
