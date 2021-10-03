<?php

namespace App\Services\ProcessExec;


use App\Services\ProcessExec\Events\EventObserver;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessReady;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessStarted;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessRunning;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessErrorOccurred;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessCanceled;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessFinished;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessSucceed;

class ProcessObserver extends EventObserver {
  
  
  protected $events = [
    ProcessReady::class,
    ProcessStarted::class,
    ProcessRunning::class,
    ProcessErrorOccurred::class,
    ProcessCanceled::class,
    ProcessSucceed::class,
    ProcessFinished::class,
  ];
  
}