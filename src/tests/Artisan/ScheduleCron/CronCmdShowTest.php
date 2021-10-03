<?php

namespace Tests\Artisan\ScheduleCron;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\User;
use App\Console\Commands\CronEntryShow;

class CronCmdShowTest extends ArtisanCallTestCase {
  
  
  public function test_schedule_cron_entry_show () {
    // prepare
    $name = __METHOD__.time();
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
    $entry->refresh();
    
    // show table
    $cmd = $this->getCommandName( CronEntryShow::class );
    $ret = $this->artisan_call( $cmd, ['id' => $last_id] );
    $this->assertStringContainsString( $entry->name, $ret );
    $this->assertStringContainsString( $entry->shell, $ret );
    $this->assertStringContainsString( $entry->command, $ret );
    $this->assertStringContainsString( $entry->cron_date, $ret );
  }
}
