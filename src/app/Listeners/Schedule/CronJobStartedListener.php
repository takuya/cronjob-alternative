<?php

namespace App\Listeners\Schedule;


use App\Models\Services\CronLogService;
use App\Events\Schedule\CronJobEvent;

class CronJobStartedListener extends CronJobEventListener {
  
  /**
   * Handle the event.
   *
   * @param CronJobEvent $event
   * @return void
   */
  public function handle ( CronJobEvent $event ) {
    CronLogService::addLog( $event->job );
  }
}
