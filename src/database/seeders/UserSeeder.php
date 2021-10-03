<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run () {
    User::create( [
      'name' => 'admin',
      'email' => 'cron@example.com',
      'password' => \Hash::make( 'h5ASmNTQ35JzAXwp' ),
    ] );
  }
}
