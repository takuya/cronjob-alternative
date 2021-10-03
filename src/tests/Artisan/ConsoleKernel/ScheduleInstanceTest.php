<?php

namespace Tests\Artisan\ConsoleKernel;

use Tests\Artisan\ArtisanCallTestCase;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Kernel;
use Symfony\Component\Console\Output\BufferedOutput;
use Illuminate\Console\Scheduling\Event;
use Str;

class ScheduleInstanceTest extends ArtisanCallTestCase {
  
  public function test_schedule_kernel_add_dynamically () {
    $name = __METHOD__.time();
    $cmd = 'echo hello world';
    $desc = Str::random( 10 );
    $cron = '* * * * *';
    
    /** @var Kernel $kernel */
    $kernel = $this->app->make( Kernel::class );
    /** @var Schedule $schedule */
    $schedule = app( Schedule::class );
    //
    $schedule->exec( $cmd )
             ->description( $desc )
             ->cron( $cron );
    
    $events = collect( $schedule->events() );
    /** @var Event $ev */
    $ev = $events[0];
    
    //dd($ev);
    
    $this->assertEquals( $cron, $ev->getExpression() );
    $this->assertEquals( $desc, ( function() { return $this->description; } )->call( $ev ) );
    $this->assertEquals( $cmd, ( function() { return $this->command; } )->call( $ev ) );
    
    $kernel->call( 'schedule:list', [], $output = new BufferedOutput() );
    $ret = $output->fetch();
    $this->assertStringContainsString( $desc, $ret );
  }
}
