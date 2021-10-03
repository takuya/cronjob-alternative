<?php

namespace App\Services\ProcessExec\Events;

/**
 *
 */
class GenericEvent {
  
  /**
   * @var object EventEmitter
   */
  protected $eventSource;
  
  /**
   * @param $object
   */
  public function __construct ( $object ) {
    $this->eventSource = $object;
  }
  
  /**
   * @return object
   */
  public function getEventSource (): object {
    return $this->eventSource;
  }
  
}