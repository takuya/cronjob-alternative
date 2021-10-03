<?php

namespace Tests\Artisan;

use Tests\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CreatesApplication;
use App\Console\Kernel;


class ArtisanCallTestCase extends TestCase {
  
  use DatabaseTransactions;
  use CreatesApplication;
  use DatabaseMigrations;
  
  
  //
  protected $kernel;
  
  /**
   * @param string $command
   * @param array  $parameters
   * @return string
   */
  public function artisan_call ( $command, $parameters = [] ): string {
    $this->app = $this->createApplication();
    /** @var Kernel $kernel */
    $kernel = $this->app->make( Kernel::class );
    $kernel->call( $command, $parameters, $output = new BufferedOutput() );
    return $output->fetch();
  }
  
  public function getCommandName ( string $class_name ) {
    $cmd = $this->app->make( $class_name );
    $name = ( function() { return $this->getName(); } )->call( $cmd );
    return $name;
  }
  
  /**
   * The CreatesApplication
   * Laravel includes a CreatesApplication trait that is
   * applied to your application's base TestCase class.
   * This trait contains a createApplication method that
   * bootstraps the Laravel application before running
   * your tests.
   * It's important that you leave this trait at its
   * original location as some features, such as Laravel's
   * parallel testing feature, depend on it.
   */
  protected function setUp (): void {
    // ここで初期化するのがポイント
    // 呼び出し順が大事なので変えないで！
    parent::setUp();
    // この２行でワンペア
    $this->app = $this->createApplication();
    $this->seed( UserSeeder::class );
    //　この２行でワンペア
    $this->app = $this->createApplication();
    //$this->beginDatabaseTransaction();
    //
  }
  
  
}