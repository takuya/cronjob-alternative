<?php

namespace Tests\Artisan\ScheduleCron;

use Tests\TestCase;
use App\Models\User;
use App\Models\CronEntry;
use App\Console\Commands\CronEntryExport;
use Tests\Artisan\ArtisanCallTestCase;

class CronCmdExportTest extends ArtisanCallTestCase {

  public function test_artisan_export_one () {
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
    
    $entry = CronEntry::find($entry->id);
    $command = $this->getCommandName( CronEntryExport::class );
    $ret = $this->artisan_call( $command, [
      'id'=>$last_id
    ] );
    $this->assertEquals(json_decode($entry->toJson()),json_decode($ret));
  }
  public function test_artisan_export_all () {
    //
    $user = User::find( 1 );
    
    $name = __METHOD__.time();
    $entry = new CronEntry();
    $entry->command = 'sleep 3000';
    $entry->name = $name;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    $entry->save();
    $last_id = $entry->id;
    
    $command = $this->getCommandName( CronEntryExport::class );
    $ret = $this->artisan_call( $command, [] );
    $arr = json_decode($ret);
    $this->assertEquals(1, sizeof($arr));
    $this->assertEquals(1, $arr[0]->id);
  }
  
}
