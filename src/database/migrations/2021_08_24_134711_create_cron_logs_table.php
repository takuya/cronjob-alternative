<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronLogsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up () {
    Schema::create( 'cron_logs', function( Blueprint $table ) {
      $table->id();
      $table->string( 'schedule_id' );
      $table->string( 'name' );
      $table->string( 'stdout' )->nullable();
      $table->string( 'stderr' )->nullable();
      $table->integer( 'exit_status_code' )->nullable();
      $table->float('operating_time')->nullable();
      $table->integer('pid')->nullable();
      $table->json( 'cron_entry' );
      $table->timestamps();
    } );
  }
  
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down () {
    Schema::dropIfExists( 'cron_logs' );
  }
}
