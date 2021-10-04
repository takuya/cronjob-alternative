<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronLog;
use App\Jobs\CronJobKillJob;

class CronJobKill extends Command {
  protected $signature = 'schedule:cron_job_kill {cron_log_id}';
  protected $description = 'kill running cron job.';
  
  
  protected function passToCronJobQueue( $log_entry, $connection='database',$name='default'){
    $job = CronJobKillJob::dispatch($log_entry)
                         ->onConnection($connection)
                         ->onQueue($name);
  
  
  }
  public function handle () {
    $log_id = $this->argument( 'cron_log_id' );
    $log_entry = CronLog::findOrFail($log_id);
    $this->passToCronJobQueue($log_entry);
    
  }
}
