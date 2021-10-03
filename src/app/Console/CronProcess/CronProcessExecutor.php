<?php

namespace App\Console\CronProcess;

use App\Services\ProcessExec\ProcessObserver;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessStarted;
use App\Events\Schedule\CronJobReady;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessRunning;
use App\Events\Schedule\CronJobRunning;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessErrorOccurred;
use App\Events\Schedule\CronJobFailed;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessFinished;
use App\Events\Schedule\CronJobFinished;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessSucceed;
use App\Events\Schedule\CronJobSuccess;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessReady;
use App\Events\Schedule\CronJobStarted;


class CronProcessExecutor implements CronProcess {
  
  use CronProcessExecutorTrait;
  
  protected $eventMappingList = [
    [ProcessReady::class, CronJobReady::class],
    [ProcessStarted::class, CronJobStarted::class],
    [ProcessRunning::class, CronJobRunning::class],
    [ProcessSucceed::class, CronJobSuccess::class],
    [ProcessErrorOccurred::class, CronJobFailed::class],
    [ProcessFinished::class, CronJobFinished::class],
  ];
  
  public function start () {
    return $this->handle();
  }
  
  public function handle () {
    $this->addRandomWait();
    $executor = $this->executor;
    $observer = $this->mapProcessEventToLaravelEvent();
    $executor->addObserver( $observer );
    //
    $executor->start();
    return $this->executor->getSubProcessId();
  }
  
  protected function addRandomWait () {
    if ( $this->entry->random_wait ) {
      $observer = new ProcessObserver();
      $observer->addEventListener( ProcessReady::class, function() {
        $random_wait = new RandomWaitExecutor( $this->entry );
        $random_wait->setScheduleId( $this->schedule_id );
        $random_wait->start();
      } );
      $this->executor->addObserver( $observer );
    }
  }
  
  protected function mapProcessEventToLaravelEvent () {
    $observer = new ProcessObserver();
    $mappingList = $this->eventMappingList;
    foreach ( $mappingList as $mapping ) {
      [$src_classname, $dst_classname] = $mapping;
      $observer->addEventListener( $src_classname, function() use ( $dst_classname ) {
        event( new $dst_classname( $this ) );
      } );
    }
    return $observer;
  }
}