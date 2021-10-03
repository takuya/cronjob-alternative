<?php

namespace App\Services\ProcessExec\Events\ProcessEvents;

use App\Services\ProcessExec\ProcessExecutor;
use App\Services\ProcessExec\Events\GenericEvent;

class ProcessEvent extends GenericEvent {
  
  public function __construct ( ProcessExecutor $executor ) {
    parent::__construct( $executor );
  }
  
  /**
   * @return ProcessExecutor
   */
  public function getExecutor (): ProcessExecutor {
    return $this->getEventSource();
  }
}