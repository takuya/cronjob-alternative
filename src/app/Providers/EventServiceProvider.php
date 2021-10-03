<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\Schedule\CronJobSuccessListener;
use App\Listeners\Schedule\CronJobFailedListener;
use App\Listeners\Schedule\CronJobFinishedListener;
use App\Events\Schedule\CronJobFinished;
use App\Events\Schedule\CronJobReady;
use App\Events\Schedule\CronJobSuccess;
use App\Events\Schedule\CronJobFailed;
use App\Events\Schedule\CronJobRunning;
use App\Listeners\Schedule\CronJobRunningListener;
use App\Listeners\Schedule\CronJobReadyListener;
use App\Events\Schedule\CronJobStarted;
use App\Listeners\Schedule\CronJobStartedListener;
use App\Events\Schedule\RandomWaitFinished;
use App\Listeners\Schedule\RandomWaitListener;
use App\Events\Schedule\RandomWaitStarted;
use App\Events\Schedule\RandomWaitRunning;

class EventServiceProvider extends ServiceProvider {
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [
    CronJobReady::class => [
      CronJobReadyListener::class,
    ],
    CronJobStarted::class => [
      CronJobStartedListener::class,
    ],
    CronJobSuccess::class => [
      CronJobSuccessListener::class,
    ],
    CronJobFailed::class => [
      CronJobFailedListener::class,
    ],
    CronJobFinished::class => [
      CronJobFinishedListener::class,
    ],
    CronJobRunning::class =>[
      CronJobRunningListener::class
    ],
    RandomWaitFinished::class=>[
      RandomWaitListener::class
    ],
    RandomWaitStarted::class=>[
      RandomWaitListener::class
    ],
    RandomWaitRunning::class=>[
      RandomWaitListener::class
    ],
  ];
  
  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot () {
    parent::boot();
  }
}
