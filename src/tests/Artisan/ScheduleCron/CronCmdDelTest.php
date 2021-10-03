<?php

namespace Tests\Artisan\ScheduleCron;

use Tests\Artisan\ArtisanCallTestCase;
use App\Models\CronEntry;
use App\Models\User;
use App\Console\Commands\CronEntryList;
use App\Console\Commands\CronEntryDel;

class CronCmdDelTest extends ArtisanCallTestCase {
  
  
  public function test_schedule_cron_entry_del () {
    $name = __METHOD__.time();
    
    $cmd = $this->app->make( CronEntryDel::class );
    $qes = ( function() { return $this->question_string; } )->call( $cmd );
    
    
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
    
    
    $cmd = $this->getCommandName( CronEntryDel::class );
    $this->artisan( $cmd, ['id' => $last_id] )
         ->expectsConfirmation( $qes, 'yes' )
         ->run();
    
    
    $ret = $this->artisan_call( $this->getCommandName( CronEntryList::class ) );
    $this->assertStringNotContainsString( $name, $ret );
  }
}
