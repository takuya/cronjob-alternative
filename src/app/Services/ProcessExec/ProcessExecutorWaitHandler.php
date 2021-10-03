<?php

namespace App\Services\ProcessExec;

trait ProcessExecutorWaitHandler {
  
  
  /** @var int */
  public $watch_interval = 10;
  
  protected function updateWatcher ( callable $callback ): callable {
    $last_called_at = microtime( true );
    return function() use ( &$last_called_at, $callback ) {
      $duration = microtime( true ) - $last_called_at;
      if ( $duration > $this->watch_interval ) {
        call_user_func( $callback );
        $last_called_at = microtime( true );
      }
    };
  }
  
}