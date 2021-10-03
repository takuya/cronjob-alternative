<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\CronProcess\CronProcess;
use App\Console\CronProcess\ForkedCronProcessExecutor;

class AppServiceProvider extends ServiceProvider {
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register () {
    app()->bind(CronProcess::class, ForkedCronProcessExecutor::class );
  }
  
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot () {
    //
  }
}
