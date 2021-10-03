<?php

namespace App\Listeners\Schedule;


use App\Events\Schedule\CronJobEvent;

abstract class CronJobEventListener {
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct () {
    //
  }
  
  /**
   * Handle the event.
   *
   * @param CronJobEvent $event
   * @return void
   */
  public function handle ( CronJobEvent $event ) {
  }
}
