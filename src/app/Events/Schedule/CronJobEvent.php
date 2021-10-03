<?php

namespace App\Events\Schedule;


use Illuminate\Broadcasting\Channel;
use App\Console\CronProcess\CronProcess;
use Illuminate\Broadcasting\PrivateChannel;


abstract class CronJobEvent {
  /**
   * @var CronProcess
   */
  public $job;
  
  public function __construct ( CronProcess $job ) {
    $this->job = $job;
  }
  /**
   * Get the channels the event should broadcast on.
   *
   * @return Channel|array
   */
  public function broadcastOn () {
    return new PrivateChannel( 'channel-name' );
  }
}