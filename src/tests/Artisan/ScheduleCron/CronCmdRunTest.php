<?php

namespace Tests\Artisan\ScheduleCron;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\User;
use App\Console\Commands\CronEntryRun;
use App\Models\Services\CronLogService;
use Str;

class CronCmdRunTest extends ArtisanCallTestCase {
  
  
  public function test_schedule_cron_entry_run () {
    // prepare
    $name = __METHOD__.time();
    $user = User::find( 1 );
    $string = Str::random( 10 );
    //
    $entry = new CronEntry();
    $entry->command = 'echo -n '.$string;
    $entry->name = $name;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $last_id = $entry->id;
    $entry->refresh();
    
    $cmd = $this->getCommandName( CronEntryRun::class );
    ob_start();
    $this->artisan_call( $cmd, ['id' => $last_id] );
    $out = ob_get_contents();
    ob_end_clean();
    $this->assertStringContainsString( 'Started', $out );
    usleep( 1000 * 150 );
    $log = CronLogService::findLastLogByCronEntry( $entry );
    while ( $log->isRunning() ) {
      usleep( 1000 * 100 );
      $log->refresh();
    }
    $this->assertEquals( $string, $log->stdout );
  }
}
