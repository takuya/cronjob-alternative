<?php

namespace App\Console\CronProcess;

use App\Models\CronEntry;
use App\Models\CronLog;
use Illuminate\Support\Str;
use App\Services\ProcessExec\ProcessExecutor;
use Symfony\Component\Process\Process;
use Exception;

trait CronProcessExecutorTrait {
  /**
   * @var ProcessExecutor
   */
  protected $executor;
  
  
  /**
   * @var CronEntry
   */
  protected $entry;
  /**
   * @var CronLog
   */
  protected $cron_log;
  /**
   * @var string
   */
  protected $schedule_id;
  
  public function __construct ( CronEntry $job ) {
    $this->entry = $job;
    $this->schedule_id = Str::random( 10 );
    $this->prepareExecutor();
  }
  
  protected function prepareExecutor () {
    $exec_struct = new CronEntryProcessAdaptor( $this->entry );
    $this->executor = new ProcessExecutor( $exec_struct );
  }
  
  public function getCronEntry (): CronEntry {
    return $this->entry;
  }
  
  public function getScheduleId (): ?string {
    return $this->schedule_id;
  }
  
  public function getSubProcessId () {
    return $this->executor->getProcess()->getPid();
  }
  
  public function getOperatingTime (): float {
    $start = $this->executor->getProcess()->getStartTime();
    return microtime( true ) - $start;
  }
  
  public function __invoke () {
    $this->start();
    return 0;
  }
  
  /**
   * @return ProcessExecutor
   */
  public function getExecutor (): ProcessExecutor {
    return $this->executor;
  }
  
  /**
   * @return Exception
   */
  public function getLastException (): Exception {
    return $this->executor->getLastException();
  }
  
  public function getProcess (): Process {
    return $this->executor->getProcess();
  }
  
}