<?php

namespace App\Notifications\Scheduling;

use Illuminate\Bus\Queueable;
use App\Console\CronProcess\CronProcess;
use Illuminate\Notifications\Notification;
use NotificationChannels\Pushover\PushoverChannel;
use NotificationChannels\Pushover\PushoverMessage;
use App\Models\CronLog;

class CronJobFinishedNotification extends Notification {
  use Queueable;
  
  
  public function via ( $notifiable ) {
    return [PushoverChannel::class];
  }
  
  public function toPushover ( $notifiable ) {
    /** @var CronProcess $notifiable */
    $schedule_id = $notifiable->getScheduleId();
    $log = CronLog::where('schedule_id',$schedule_id)->firstOrFail();
    $r = PushoverMessage::create( 'cron実行結果'.$log->stdout )
                        ->title( $log->name );
    
    
    return $r;
  }
}
