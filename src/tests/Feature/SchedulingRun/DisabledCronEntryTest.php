<?php

namespace Tests\Feature\SchedulingRun;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\User;

class DisabledCronEntryTest extends ArtisanCallTestCase {
  
  
  public function test_disabled_cron_entry_does_not_listed () {
    //
    $user = User::find( 1 );
    
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'pwd';
    $entry->name = $name;
    $entry->cwd = storage_path( 'logs' );
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $entry->refresh();
    
    $ret = $this->artisan_call( 'schedule:list' );
    $this->assertStringContainsString( $name, $ret );
    
    $entry->enabled = false;
    $entry->save();
    
    
    $this->assertDatabaseHas( ( new CronEntry() )->getTable(), [
      'name' => $name,
      'enabled' => false,
    ] );
    
    $ret = $this->artisan_call( 'schedule:list' );
    $this->assertStringNotContainsString( $name, $ret );
  }
}
