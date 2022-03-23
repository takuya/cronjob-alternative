<?php

namespace App\Models\Repositories;

use Illuminate\Console\Scheduling\Schedule;
use App\Models\CronEntry;
use Cron\CronExpression;
use Illuminate\Support\Carbon;

class CronEntryRepository {
  
  /**
   * @var CronEntry
   */
  protected $entry;
  
  public function __construct ( CronEntry $entry ) {
    $this->entry = $entry;
  }
  
  public static function nextDueDate(string $cron_expression, $timezone){
    $timezone = is_string($timezone) ? new \DateTimeZone($timezone) : $timezone;
    return (new CronExpression( $cron_expression ))
      ->getNextRunDate( Carbon::now()->setTimezone( config( 'app.timezone' ) ) )
      ->setTimezone( $timezone )
      ->format( 'Y-m-d H:i:s P' );
  }
  public static function lastDueDate(string $cron_expression, $timezone){
    $timezone = is_string($timezone) ? new \DateTimeZone($timezone) : $timezone;
    return (new CronExpression( $cron_expression ))
      ->getPreviousRunDate( Carbon::now()->setTimezone( config( 'app.timezone' ) ) )
      ->setTimezone( $timezone )
      ->format( 'Y-m-d H:i:s P' );
  }
  
  public static function scheduling_all_entries ( Schedule $schedule ) {
    foreach ( CronEntry::getEnabledEntries() as $item ) {
      $repo = new CronEntryRepository( $item );
      $repo->scheduling( $schedule );
    }
  }
  
  public function scheduling ( Schedule $schedule ) {
    return $schedule->command( "schedule:cron_run", [$this->entry->id] )
                    ->name( $this->entry->name )
                    ->cron( $this->entry->cron_date );
  }
  
  
}