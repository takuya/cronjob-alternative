<?php

namespace App\Services;

use Illuminate\Events\Dispatcher;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Kernel;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Collection;
use App\Console\Commands\CronEntryRun;
use App\Models\Repositories\CronEntryRepository;
use Illuminate\Support\Str;
use App\Models\CronEntry;
use App\Models\Services\CronLogService;

class ScheduleService {
  
  /**
   * @return Collection|Event[]
   */
  public static function getSchedules () {
    new Kernel( app(), new Dispatcher() );
    $schedule = app( Schedule::class );
    return collect( $schedule->events() );
  }
  
  /**
   * @return array
   */
  public static function getSchedulesAsArray( $sort_order=null):array{
    $scheduledCommands = static::getSchedules();
    //
    $run_cmd = ArtisanCmdService::getCommandName(CronEntryRun::class);
    $scheduledCommands = $scheduledCommands
      ->map(
      function ( $e ) use ( $run_cmd ) {
      
        $next_time = CronEntryRepository::nextDueDate($e->expression, $e->timezone);
        $last_time = CronEntryRepository::lastDueDate($e->expression, $e->timezone);
        $last = date_diff_for_humans_jp($last_time, " - 30 seconds ");
        $next = date_diff_for_humans_jp($next_time, " + 30 seconds ");
        $id = (string)Str::of($e->command)->match("/${run_cmd} (\d+)$/");
        
        
        if( $id ) {
          $entry = CronEntry::find($id);
          $log = CronLogService::findLastLogByCronEntry($entry);
          $last = $log ? date_diff_for_humans_jp( $last_time, $log->created_at ?? 'now') : '─';
          $last_time = $log ? $last_time : '─';
        }
      
        return [
          'artisan' => $e->command,
          'cron' => $e->expression,
          'desc' => $e->description,
          'last' => $last,
          'next' => $next,
          'last_time' => $last_time,
          'next_time' => $next_time,
          'cron_entry_id' => $id,
        ];
      });
    
    
    ///
    switch (strtolower($sort_order)){
      case "desc":
        return $scheduledCommands
          ->sortByDesc(function($e){ return strtotime($e['next_time']); })
          ->values()
          ->toArray();
      case "asc":
        return $scheduledCommands
          ->sortBy(function($e){ return strtotime($e['next_time']); })
          ->values()
          ->toArray();
      default:
        return $scheduledCommands->toArray();
    }
  }
  
}