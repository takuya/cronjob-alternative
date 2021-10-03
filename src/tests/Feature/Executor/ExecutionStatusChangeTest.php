<?php

namespace Tests\Feature\Executor;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use App\Models\CronEntry;
use App\Services\SyntaxCheck\Exceptions\ShellCommandNotFoundException;
use App\Console\CronProcess\CronProcessExecutor;
use App\Services\ProcessExec\ProcessObserver;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessStarted;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessEvent;
use App\Models\Services\CronLogService;
use App\Events\Schedule\RandomWaitRunning;
use App\Events\Schedule\CronJobEvent;
use App\Console\CronProcess\RandomWaitExecutor;
use App\Listeners\Schedule\RandomWaitListener;
use Illuminate\Support\Facades\Event;
use App\Events\Schedule\CronJobRunning;
use App\Events\Schedule\CronJobStarted;
use App\Events\Schedule\CronJobFinished;

class ExecutionStatusChangeTest extends TestCase {
  
  use RefreshDatabase;
  
  protected function db_seed(){
    $this->seed(UserSeeder::class);
    $user = User::find( 1 );
    $entry = new CronEntry();
    $entry->shell = 'php';
    $entry->command = '<?php  usleep(100); echo "end";';
    $entry->name = __METHOD__;
    $entry->random_wait=1;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $entry->refresh();
  }
  public function test_execution_status_change_to_waiting_detection(){
    // database
    $this->db_seed();
    // executor
    $job = new CronProcessExecutor(CronEntry::find(1) );
    // detect status change by event.
    $called = false;
    Event::listen( RandomWaitRunning::class,
      function( CronJobEvent $ev ) use (&$called){
        /** @var RandomWaitExecutor $executor */
        $entry = $ev->job->getCronEntry();
        $log = CronLogService::findLastLogByCronEntry( $entry );
        $this->assertTrue($log->isWaiting());
        $called = true;
      } );
    $job->start();
    $this->assertTrue($called);
  }
  public function test_execution_status_change_to_executing_detection(){
    // database
    $this->db_seed();
    // executor
    $job = new CronProcessExecutor(CronEntry::find(1) );
    // detect status change by event.
    $called = false;
    Event::listen( CronJobStarted::class,
      function( CronJobEvent $ev ) use (&$called){
        /** @var RandomWaitExecutor $executor */
        $entry = $ev->job->getCronEntry();
        $log = CronLogService::findLastLogByCronEntry( $entry );
        $this->assertTrue($log->isRunning());
        $called = true;
      } );
    $job->start();
    $this->assertTrue($called);
  }
  public function test_execution_status_change_to_finished_detection(){
    // database
    $this->db_seed();
    // executor
    $job = new CronProcessExecutor(CronEntry::find(1) );
    // detect status change by event.
    $called = false;
    Event::listen( CronJobFinished::class,
      function( CronJobEvent $ev ) use (&$called){
        /** @var RandomWaitExecutor $executor */
        $entry = $ev->job->getCronEntry();
        $log = CronLogService::findLastLogByCronEntry( $entry );
        $this->assertTrue($log->isFinished());
        $called = true;
      } );
    $job->start();
    $this->assertTrue($called);
  }
}