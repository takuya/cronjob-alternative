<?php

namespace Tests\Feature\Executor;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use App\Models\User;
use App\Models\CronEntry;
use App\Console\CronProcess\ForkedCronProcessExecutor;
use App\Models\Services\CronLogService;
use Tests\TestCase;
use App\Jobs\CronJobKillJob;
use App\Services\ProcessExec\ProcessObserver;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessCanceled;
use Illuminate\Support\Facades\Event;
use App\Events\Schedule\RandomWaitRunning;
use App\Events\Schedule\CronJobEvent;
use App\Console\CronProcess\RandomWaitExecutor;

class CronCmdRunKillTest extends TestCase {
  
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
  public function test_kill_random_waiting(){
    // database
    $this->db_seed();
    $entry = CronEntry::find(1);
    $entry->random_wait=1800;
    $entry->save();
    $entry->refresh();
    // job and inner executor
    $job = new ForkedCronProcessExecutor($entry);
    Event::listen( RandomWaitRunning::class,
      function( CronJobEvent $ev ) {
        /** @var RandomWaitExecutor $executor */
        $entry = $ev->job->getCronEntry();
        $log = CronLogService::findLastLogByCronEntry( $entry );
        //$this->assertTrue($log->isWaiting());
        dump($log);
    } );
  
    //
    $job->start();
    usleep(100);
    //
    usleep(1000*5);
    $log = CronLogService::findLastLogByCronEntry( $entry );
    dump($log);
    $job = CronJobKillJob::dispatch($log)
                         ->onConnection('sync')
                         ->onQueue('default');
    
  
  
  }
  
}