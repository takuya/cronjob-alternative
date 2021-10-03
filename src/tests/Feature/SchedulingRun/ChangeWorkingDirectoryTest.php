<?php

namespace SchedulingRun;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\CronLog;
use App\Models\User;
use App\Console\CronProcess\CronProcessExecutor;

class ChangeWorkingDirectoryTest extends ArtisanCallTestCase {
  
  
  public function test_schedule_run_with_working_directory () {
    //
    $user = User::find( 1 );
    //
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'pwd';
    $entry->name = $name;
    $entry->cwd = storage_path( 'logs' );
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $last_id = $entry->id;
    //
    $job = new CronProcessExecutor(CronEntry::find($last_id ) );
    $job->handle();
    $log = CronLog::where( 'name', $name )->firstOrFail();
    $this->assertEquals( storage_path( 'logs' ), trim( $log->stdout ) );
  }
}
