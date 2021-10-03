<?php

namespace App\Console\CronProcess;

use Symfony\Component\Process\Process;
use Exception;
use App\Models\CronEntry;
use App\Exceptions\NotImplentedException;
use App\Events\Schedule\RandomWaitStarted;
use App\Events\Schedule\RandomWaitRunning;
use App\Events\Schedule\RandomWaitFinished;

class RandomWaitExecutor implements CronProcess {
  
  protected $schedule_id;
  protected $entry;
  protected $started_at;
  protected $decided_wait_time;
  
  public function getDecidedWaitTime () {
    return $this->decided_wait_time;
  }
  
  public function __construct ( CronEntry $job ) {
    $this->entry = $job;
  }
  
  public function getScheduleId () {
    return $this->schedule_id;
  }
  
  public function setScheduleId ( $schedule_id ) {
    $this->schedule_id = $schedule_id;
  }
  
  public function __invoke () {
    $this->start();
  }
  
  public function start () {
    $random_max = $this->entry->random_wait;
    /** @var float $wait_time micro time */
    $wait_time = rand( 1, $random_max );
    $this->decided_wait_time = $wait_time;
    
    $this->wait( $this->decided_wait_time );
  }
  
  protected function wait ( $wait_time ) {
    //TODO::キャンセル時の取り扱い。
    $this->started_at = microtime( true );
    
    event( new RandomWaitStarted( $this ) );
    
    while ( $wait_time > ( $this->getOperatingTime() ) ) {
      usleep( 1000 * 1000 );
      event( new RandomWaitRunning( $this ) );
    }
    
    event( new RandomWaitFinished( $this ) );
  }
  
  public function getOperatingTime () {
    return microtime( true ) - $this->started_at;
  }
  
  public function getCronEntry (): CronEntry {
    return $this->entry;
  }
  
  public function getSubProcessId () {
    throw new NotImplentedException();
  }
  
  public function getLastException (): Exception {
    throw new NotImplentedException();
  }
  
  public function getProcess (): Process {
    throw new NotImplentedException();
  }
  
}