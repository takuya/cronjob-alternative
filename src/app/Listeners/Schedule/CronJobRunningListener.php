<?php

namespace App\Listeners\Schedule;

use App\Models\Services\CronLogService;
use App\Events\Schedule\CronJobEvent;

class CronJobRunningListener extends CronJobEventListener {
  
  public function handle ( CronJobEvent $event ) {
    CronLogService::addLog( $event->job );
  }
}
