<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Eloquent;
use Illuminate\Support\Carbon;
use App\Listeners\Schedule\RandomWaitListener;
use Illuminate\Support\Str;

/**
 * App\Models\CronLog
 *
 * @property int         $id
 * @property string|null $user
 * @property string      $shell
 * @property string      $body
 * @property string      $stdout
 * @property string|null $stderr
 * @property string      $exit_status_code
 * @property string|null $cron_entry
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CronLog newModelQuery()
 * @method static Builder|CronLog newQuery()
 * @method static Builder|CronLog query()
 * @method static Builder|CronLog whereBody( $value )
 * @method static Builder|CronLog whereCreatedAt( $value )
 * @method static Builder|CronLog whereCronEntry( $value )
 * @method static Builder|CronLog whereExitStatusCode( $value )
 * @method static Builder|CronLog whereId( $value )
 * @method static Builder|CronLog whereShell( $value )
 * @method static Builder|CronLog whereStderr( $value )
 * @method static Builder|CronLog whereStdout( $value )
 * @method static Builder|CronLog whereUpdatedAt( $value )
 * @method static Builder|CronLog whereUser( $value )
 * @mixin Eloquent
 * @property string      $name
 * @method static Builder|CronLog whereName( $value )
 * @property Carbon|null $started_at
 * @property Carbon|null $finished_at
 * @method static Builder|CronLog whereFinishedAt( $value )
 * @method static Builder|CronLog whereStartedAt( $value )
 * @property string      $schedule_id
 * @property float|null  $operating_time
 * @method static Builder|CronLog whereOperatingTime( $value )
 * @method static Builder|CronLog whereScheduleId( $value )
 * @property int|null $pid
 * @method static Builder|CronLog wherePid($value)
 */
class CronLog extends Model {
  
  protected $casts = [
    'cron_entry' => 'json',
    'operating_time' => 'float',
    'exit_status_code' => 'int',
  ];
  
  protected $guarded = [];
  
  public function getCronEntry(){
    return CronEntry::find($this->cron_entry['id']);
  }
  
  public function setCronEntryAttribute ( $value ) {
    if ( $value instanceof CronEntry ) {
      $cron_entry = $value;
      $this->name = $cron_entry->name;
    }
    $this->attributes['cron_entry'] = $value;
  }
  public function isFinished(){
    return ! is_null($this->exit_status_code);
  }
  public function isError(){
    return $this->isFinished() && ($this->exit_status_code !==0 );
  }
  public function isRunning(){
    return !$this->isFinished();
  }
  public function isWaiting(){
    $message = RandomWaitListener::$waiting_message;
    return Str::of($this->stdout)->matchAll("/^{$message}.+sec$/")->count()>0;
  }
  public function getStatus(){
  
    $status = '';
    $this->isFinished()  && $status = '終了';
    $this->isError()     && $status = 'エラー';
    $this->isRunning()   && $status = '実行中';
    $this->isWaiting()   && $status = 'waiting';
    return $status;
  }
}
