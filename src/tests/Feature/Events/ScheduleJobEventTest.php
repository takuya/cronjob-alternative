<?php

namespace Tests\Feature\Events;

use App\Models\User;
use App\Models\CronEntry;
use Tests\Artisan\ArtisanCallTestCase;
use App\Models\Services\CronLogService;
use App\Console\CronProcess\CronProcessExecutor;
use App\Services\ProcessExec\ProcessObserver;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessRunning;
use App\Services\ProcessExec\Events\ProcessEvents\ProcessEvent;

class ScheduleJobEventTest extends ArtisanCallTestCase {
  
  
  public function test_on_running_listener_get_verbose_output () {
    $user = User::find( 1 );
    
    
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = <<<'EOS'
    <?php
      for( $i=0;$i<10;$i++){
        echo "Hello".PHP_EOL;
        usleep(1000*3);
      }
      
    EOS;
    
    $entry->name = $name;
    $entry->shell = 'php';
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $entry->refresh();
    //
    $job = new CronProcessExecutor($entry );
    $interval = ( 1 / 1000 );
    $len = -1;
    $counter = 0;
    $last_called = microtime( true );
    
    $job->getExecutor()->watch_interval = $interval;
    $observer = new ProcessObserver();
    $job->getExecutor()->addObserver( $observer );
    //
    $observer->addEventListener( ProcessRunning::class,
      function( ProcessEvent $event ) use ( $entry, &$len, &$counter, &$interval, &$last_called ) {
        $log = CronLogService::findLastLogByCronEntry( $entry );
        $this->assertGreaterThanOrEqual( $len, strlen( $log->stdout ) );
        $len = strlen( $log->stdout );
        $this->assertTrue( $interval < microtime( true ) - $last_called );
        $last_called = microtime( true );
        $counter++;
      } );
    $job->handle();
    
    $this->assertGreaterThan( 0, $counter );
  }
}
