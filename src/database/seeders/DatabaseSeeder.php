<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run () {
    if (  preg_match('/dev|local/', config('app.env')) ){
      $this->call(UserSeeder::class);
      $this->call(CronEntrySeeder::class);
    }else{
      $this->call(ProdUserSeeder::class);
      $this->call(ProdCronEntrySeeder::class);
    }
  }
}
