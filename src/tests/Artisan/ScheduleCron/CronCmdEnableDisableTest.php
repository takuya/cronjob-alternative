<?php

namespace Tests\Artisan\ScheduleCron;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\User;
use App\Console\Commands\CronEntryDisable;
use App\Console\Commands\CronEntryEnable;

class CronCmdEnableDisableTest extends ArtisanCallTestCase {
  
  
  public function test_schedule_cron_entry_disable_enable () {
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
    
    // disable
    $cmd = $this->getCommandName( CronEntryDisable::class );
    $this->artisan_call( $cmd, ['id' => $last_id] );
    $entry->refresh();
    $this->assertEquals( false, $entry->enabled );
    $ret = $this->artisan_call( 'schedule:list' );
    $this->assertStringNotContainsString( $name, $ret );
    // enable
    $cmd = $this->getCommandName( CronEntryEnable::class );
    $this->artisan_call( $cmd, ['id' => $last_id] );
    $entry->refresh();
    $this->assertEquals( true, $entry->enabled );
    $ret = $this->artisan_call( 'schedule:list' );
    $this->assertStringContainsString( $name, $ret );
  }
}
