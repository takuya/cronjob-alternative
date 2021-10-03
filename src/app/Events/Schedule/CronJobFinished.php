<?php

namespace App\Events\Schedule;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CronJobFinished extends CronJobEvent {
  use Dispatchable, InteractsWithSockets, SerializesModels;
}
