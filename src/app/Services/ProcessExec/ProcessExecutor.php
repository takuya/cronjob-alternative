<?php

namespace App\Services\ProcessExec;

use Symfony\Component\Process\Process;
use Exception;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessRunning;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessReady;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessStarted;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessErrorOccurred;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessFinished;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessSucceed;

class ProcessExecutor {
  
  use ProcessExecutorWaitHandler;
  use ProcessEventEmitter;
  use ProcessPosixSignalHandler;
  
  /**
   * @var Process
   */
  protected $proc;
  /**
   * @var ExecArgStruct
   */
  protected $struct;
  /**
   * @var Exception
   */
  protected $last_exception;
  
  public function __construct ( ExecArgStruct $struct ) {
    $this->struct = $struct;
  }
  
  public function start () {
    $this->handle();
  }
  
  protected function handle () {
    try {
      $proc = $this->proc = $this->prepare();
      $watcher = $this->updateWatcher( function() { $this->fireEvent( ProcessRunning::class ); } );
      $this->fireEvent( ProcessReady::class );
      $proc->start();
      $this->fireEvent( ProcessStarted::class );
      $proc->wait( $watcher );
      $this->fireEvent( ProcessSucceed::class );
    } catch (Exception $exception) {
      $this->last_exception = $exception;
      $this->fireEvent( ProcessErrorOccurred::class );
    } finally {
      $this->fireEvent( ProcessFinished::class );
    }
  }
  
  protected function prepare () {
    $proc = $this->struct->prepareProcess();
    $this->posix_ensure_signal_attach();
    return $proc;
  }
  
  public function getSubProcessId (): ?int {
    return $this->proc->getPid();
  }
  
  /**
   * @return Exception
   */
  public function getLastException (): Exception {
    return $this->last_exception;
  }
  
  /**
   * @return Process
   */
  public function getProcess (): Process {
    return $this->proc;
  }
  
}