<?php

namespace App\Console\CronProcess;

use Illuminate\Notifications\Notifiable;
use NotificationChannels\Pushover\PushoverReceiver;

trait NotifiableJob {
  use Notifiable;
  
  public function routeNotificationForPushover () {
    return PushoverReceiver::withUserKey( env( 'PUSHOVER_USER_TOKEN' ) )
                           ->withApplicationToken( env( 'PUSHOVER_APP_TOKEN' ) );
  }
}