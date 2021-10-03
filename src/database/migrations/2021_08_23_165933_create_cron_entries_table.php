<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronEntriesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up () {
    Schema::create( 'cron_entries', function( Blueprint $table ) {
      $table->id();
      $table->bigInteger( 'owner_id' );
      $table->string( 'cron_date' );
      $table->string( 'command' );
      $table->string( 'shell' )->default( 'bash' );
      $table->string( 'cwd' )->nullable();
      $table->string( 'user' )->nullable();
      $table->json( 'env' )->nullable();
      $table->boolean( 'enabled' )->default( true );
      $table->integer( 'random_wait' )->nullable();
      $table->string( 'name' )->nullable();
      $table->text( 'comment' )->nullable();
      $table->timestamps();
      
      //
      $table->foreign('owner_id')
        ->references('id')
        ->on('users')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      
    } );
  }
  
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down () {
    Schema::dropIfExists( 'cron_entries' );
  }
}
