<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ProdUserSeeder extends Seeder {
  
  /**
   * Run the database seeds.
   * @return void
   */
  public function run() {
    $mail = 'cron@example.com';
    $pass = Str::random(15);
    $pass = env('docker_build') ? '4jjXBxtRhUmrXBj' :$pass ;
    $user = User::create(
      [
        'name'     => 'admin',
        'email'    => $mail,
        'password' => \Hash::make($pass),
      ]);
    
    
    
    $data= [
      'login'
      =>[
        'id'=>$mail,
        'pw'=>$pass
      ]
    ];
    
    //
    \Log::notice(var_export($data,true));
    var_dump($data);
    //
  }
}
