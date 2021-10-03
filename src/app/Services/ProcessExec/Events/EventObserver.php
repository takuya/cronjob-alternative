<?php

namespace App\Services\ProcessExec\Events;


use RuntimeException;

/**
 *
 */
abstract class EventObserver {
  
  /**
   * @var string[]  list of 'GenericEvent::class'
   */
  protected $events = [];
  /**
   * @var array
   */
  protected $handlers = [];
  /**
   * @var object EventEmitter
   */
  protected $eventTarget;
  
  public function __construct () {
    foreach ( $this->events as $event ) {
      $this->handlers[$event] = [];
    }
  }
  
  /**
   * @param GenericEvent $event
   */
  public function notifyEvent ( GenericEvent $event ) {
    $event_classname = get_class( $event );
    $this->isObserving( $event_classname );
    foreach ( $this->handlers[$event_classname] as $callback ) {
      call_user_func( $callback, $event );
    }
  }
  
  /**
   * @param string $classname
   */
  protected function isObserving ( string $classname ) {
    if ( !in_array( $classname, $this->events ) ) {
      throw new RuntimeException( "$classname is not defined as observing event" );
    }
  }
  
  /**
   * @param string   $event
   * @param callable $listener
   */
  public function addEventListener ( string $event, callable $listener ) {
    $this->isObserving( $event );
    $this->handlers[$event][] = $listener;
  }
  
  /**
   * @return object
   */
  public function getEventTarget (): object {
    return $this->eventTarget;
  }
  
  /**
   * @param mixed $eventTarget
   */
  public function setEventTarget ( $eventTarget ): void {
    $this->eventTarget = $eventTarget;
  }
  
  
}