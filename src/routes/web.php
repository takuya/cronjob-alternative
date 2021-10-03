<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\SchedulingController;
use App\Http\Controllers\CronLogsController;
use App\Http\Controllers\SystemdServiceController;
use App\Http\Controllers\JournaldLogViewController;
use Symfony\Component\Process\ExecutableFinder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Auth::routes();
Auth::routes( ['register' => false] );

Route::get( '/', function() {
  return redirect( 'home' );
} );

Route::get( 'info', function() { phpinfo(); } );


Route::get( '/home', [App\Http\Controllers\HomeController::class, 'index'] )->name( 'home' );


Route::group( ['middleware' => ['auth']], function() {
  Route::prefix( 'user/')->name('user.')->group( function() {
    Route::resource( '/cron', CronJobController::class );
    Route::get( '/cron/{cron}/logs/{log}', [CronLogsController::class,'show_with_entry'] )->name('cron.logs.show');
    Route::get( '/logs', [CronLogsController::class,'index' ])->name('logs.index');
    Route::get( '/logs/{log}', [CronLogsController::class,'show' ])->name('log.show');
    Route::delete( '/logs/{log}/kill/{pid}', [CronLogsController::class,'killJob'] )->name('cron.job.kill');
    //Route::get( '/logs/{cron_log}', [CronLogsController::class,'show' ])->name('logs.show');
    Route::post( '/cron/{id}/pause', [CronJobController::class,'pause'] )->name('cron.pause');
    Route::post( '/cron/{id}/run_now', [CronJobController::class,'run_now'] )->name('cron.run_now');
  
  } );
} );

Route::group( ['middleware' => ['auth']], function() {
  Route::prefix( 'admin/')->name('admin.')->group( function() {
    Route::resource( '/scheduling', SchedulingController::class );
    Route::get( '/journald', [JournaldLogViewController::class,'show'])->name('journald_view');
    Route::get( '/systemd', [SystemdServiceController::class,'show'])->name('systemd_service.view');
  } );
} );