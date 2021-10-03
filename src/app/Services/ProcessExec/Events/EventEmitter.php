<?php

namespace App\Services\ProcessExec\Events;

/**
 *
 */
trait EventEmitter {
  /** @var EventObserver[] */
  protected $observers = [];
  
  /**
   * @param EventObserver $observer
   */
  public function addObserver ( EventObserver $observer ) {
    $observer->setEventTarget( $this );
    $this->observers[] = $observer;
  }
  
  /**
   * @param string $name Class Name of Event ( ex. SomeEventCreated::class )
   */
  public function fireEvent ( string $name ) {
    $event = new $name( $this );
    $this->handleEvent( $event );
  }
  
  /**
   * @param GenericEvent $event
   */
  public function handleEvent ( GenericEvent $event ) {
    foreach ( $this->observers as $observer ) {
      $observer->notifyEvent( $event );
    }
  }
  
}