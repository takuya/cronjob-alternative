<?php

namespace App\Listeners\Schedule;

use App\Events\Schedule\CronJobEvent;
use App\Models\CronLog;
use App\Models\Services\CronLogService;
use App\Events\Schedule\RandomWaitStarted;
use App\Events\Schedule\RandomWaitRunning;
use App\Events\Schedule\RandomWaitFinished;
use App\Console\CronProcess\RandomWaitExecutor;

class RandomWaitListener extends CronJobEventListener {
  
  public static $waiting_message = 'Random wait';
  
  public function handle ( CronJobEvent $event ) {
    

    switch (get_class( $event )) {
      case RandomWaitStarted::class :
        $this->log_started( $event );
        break;
      case RandomWaitRunning::class :
        $this->log_running( $event );
        break;
      case RandomWaitFinished::class :
        $this->log_finished( $event );
        break;
    }
  }
  
  protected function log_started ( CronJobEvent $event ) {
    /** @var RandomWaitExecutor $executor */
    $executor = $event->job;
    $schedule_id = $executor->getScheduleId();
    $wait_time = $executor->getDecidedWaitTime();
    $log = CronLog::where( 'schedule_id', $schedule_id )->firstOrNew();
    $entry = $executor->getCronEntry();
    $log->fill( [
      'schedule_id' => $schedule_id,
      'name' => $entry->name,
      'cron_entry' => $entry,
      'stdout' => static::$waiting_message." {$wait_time}sec",
      'operating_time' => $event->job->getOperatingTime(),
      'pid' => getmypid(),
    ] );
    $log->save();
  }
  
  protected function log_running ( CronJobEvent $event ) {
    $log = CronLogService::findLastLogByCronEntry( $event->job->getCronEntry() );
    $log->fill( [
      'operating_time' => $event->job->getOperatingTime(),
    ] );
    $log->save();
  }
  
  protected function log_finished ( $event ) {
    $log = CronLogService::findLastLogByCronEntry( $event->job->getCronEntry() );
    
    $log->fill( [
      'operating_time' => $event->job->getOperatingTime(),
      'stdout' => null,
      'pid'=>null,
      'created_at' =>null,
      'updated_at' =>null,
    ] );
    $log->save();
  }
}
