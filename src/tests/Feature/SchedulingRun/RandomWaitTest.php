<?php

namespace SchedulingRun;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\User;
use App\Models\Services\CronLogService;
use App\Services\ProcessExec\ProcessObserver;
use App\Console\CronProcess\CronProcessExecutor;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessEvent;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessStarted;
use App\Events\Schedule\RandomWaitRunning;
use App\Events\Schedule\CronJobEvent;
use App\Listeners\Schedule\RandomWaitListener;
use App\Events\Schedule\RandomWaitStarted;
use App\Events\Schedule\RandomWaitFinished;
use Event;
use App\Console\CronProcess\RandomWaitExecutor;

class RandomWaitTest extends ArtisanCallTestCase {
  
  public function test_random_wait_before_job_run () {
    //
    $user = User::find( 1 );
    //
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'echo -n Hello';
    $entry->name = $name;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->random_wait = 1;
    $entry->save();
    $last_id = $entry->id;
    //
    $job = new CronProcessExecutor(CronEntry::find($last_id ) );
    $executor = $job->getExecutor();
    
    $observer = new ProcessObserver();
    $observer->addEventListener( ProcessStarted::class,
      function( ProcessEvent $event ) use ( $entry ) {
        $log = CronLogService::findLastLogByCronEntry( $entry );
        $this->assertTrue( $log->operating_time > $entry->random_wait );
      } );
    $executor->addObserver( $observer );
    //
    $is_running_called = false;
    Event::listen( RandomWaitRunning::class,
      function( CronJobEvent $ev ) use ( &$is_running_called ) {
        /** @var RandomWaitExecutor $executor */
        $executor = $ev->job;
        $entry = $ev->job->getCronEntry();
        $wait = $executor->getDecidedWaitTime();
        $log = CronLogService::findLastLogByCronEntry( $entry );
        $this->assertStringContainsString( RandomWaitListener::$waiting_message, $log->stdout );
        $this->assertStringContainsString( "{$wait}sec", $log->stdout );
        $is_running_called = true;
      } );
    $is_started_called = false;
    Event::listen( RandomWaitStarted::class,
      function( CronJobEvent $ev ) use ( &$is_started_called ) {
        /** @var RandomWaitExecutor $executor */
        $executor = $ev->job;
        $entry = $executor->getCronEntry();
        $wait = $executor->getDecidedWaitTime();
        $this->assertEquals($wait,$entry->random_wait);
        $is_started_called = true;
      } );
    $is_finished_called = false;
    Event::listen( RandomWaitFinished::class,
      function( CronJobEvent $ev ) use ( &$is_finished_called ) {
        $is_finished_called = true;
        $entry = $ev->job->getCronEntry();
        $log = CronLogService::findLastLogByCronEntry( $entry );
        $this->assertNull( $log->created_at );
        $this->assertNull( $log->updated_at );
        $this->assertNull( $log->pid );
        $this->assertNull( $log->stdout );
        $this->assertNull( $log->stderr );
      } );
    $job->start();
    //
    $this->assertTrue( $is_running_called );
    $this->assertTrue( $is_finished_called );
    $this->assertTrue( $is_started_called );
  }
}
