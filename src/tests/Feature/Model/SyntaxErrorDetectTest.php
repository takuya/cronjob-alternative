<?php

namespace Tests\Feature\Model;

use Tests\TestCase;
use App\Models\CronEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use App\Models\User;
use App\Services\SyntaxCheck\Exceptions\BashInvalidSyntaxError;
use App\Services\SyntaxCheck\Exceptions\PHPInvalidSyntaxError;

class SyntaxErrorDetectTest extends TestCase {
  
  use RefreshDatabase;
  
  public function test_bash_syntax_error_detected () {
    $this->seed( UserSeeder::class );
    $user = User::find( 1 );
    
    $src = <<<'EOS'
      echo aaaaaaaaaaa
      if (())
      
      fi
    EOS;
    
    $entry = new CronEntry();
    $entry->command = $src;
    $entry->name = __METHOD__;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    
    $this->expectException( BashInvalidSyntaxError::class );
    $entry->save();
  }
  
  public function test_php_syntax_error_detected () {
    $this->seed( UserSeeder::class );
    $user = User::find( 1 );
    
    $src = '<?php $a';
    
    $entry = new CronEntry();
    $entry->command = $src;
    $entry->shell = 'php';
    $entry->name = __METHOD__;
    $entry->cron_date = '* * * * *';
    $entry->owner()->associate( $user );
    
    $this->expectException( PHPInvalidSyntaxError::class );
    $entry->save();
  }
}
