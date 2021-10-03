<?php

namespace SchedulingRun;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\CronLog;
use App\Models\User;
use App\Console\CronProcess\ForkedCronProcessExecutor;

class SignalCaughtAndLogged extends ArtisanCallTestCase {
  
  
  public function test_stop_running_by_signal_interruption () {
    //
    $user = User::find( 1 );
    //
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'sleep 3000';
    $entry->name = $name;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $last_id = $entry->id;
    //
    $job = new ForkedCronProcessExecutor(CronEntry::find($last_id ) );
    $job->start();
    usleep( 1000 * 50 );
    
    $pid = $job->getSubProcessId();
    posix_kill( $pid, SIGINT );
    usleep( 1000 * 150 );
    $log = CronLog::where( 'schedule_id', $job->getScheduleId() )->firstOrFail();
    
    $this->assertEquals( 143, $log->exit_status_code );
  }
  
  public function test_stop_running_by_signal_hup () {
    //
    $user = User::find( 1 );
    //
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'sleep 3000';
    $entry->name = $name;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $last_id = $entry->id;
    //
    $job = new ForkedCronProcessExecutor(CronEntry::find($last_id ) );
    $job->start();
    usleep( 1000 * 50 );
    
    $pid = $job->getSubProcessId();
    posix_kill( $pid, SIGHUP );
    usleep( 1000 * 50 );
    $log = CronLog::where( 'schedule_id', $job->getScheduleId() )->firstOrFail();
    
    $this->assertEquals( 143, $log->exit_status_code );
  }
  
  public function test_stop_running_by_signal_terminate () {
    //
    $user = User::find( 1 );
    //
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'sleep 3000';
    $entry->name = $name;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $last_id = $entry->id;
    //
    $job = new ForkedCronProcessExecutor(CronEntry::find($last_id ) );
    $job->start();
    usleep( 1000 * 50 );
    
    $pid = $job->getSubProcessId();
    posix_kill( $pid, SIGTERM );
    usleep( 1000 * 50 );
    $log = CronLog::where( 'schedule_id', $job->getScheduleId() )->firstOrFail();
    
    $this->assertEquals( 143, $log->exit_status_code );
  }
  
  public function test_stop_running_by_signal_quit () {
    //
    $user = User::find( 1 );
    //
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'sleep 3000';
    $entry->name = $name;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $last_id = $entry->id;
    //
    $job = new ForkedCronProcessExecutor(CronEntry::find($last_id ) );
    $job->start();
    usleep( 1000 * 50 );
    
    $pid = $job->getSubProcessId();
    posix_kill( $pid, SIGQUIT );
    usleep( 1000 * 50 );
    $log = CronLog::where( 'schedule_id', $job->getScheduleId() )->firstOrFail();
    
    $this->assertEquals( 143, $log->exit_status_code );
  }
}
