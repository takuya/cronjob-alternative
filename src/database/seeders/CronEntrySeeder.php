<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CronEntry;
use App\Models\User;

class CronEntrySeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run () {
    $cron_entry = (new CronEntry([
      'owner_id'=> User::find(1)->id,
      'cron_date'=>'*/3 * * * *',
      'command'=>'sleep 65; echo end; ',
      'name'=>'65秒タスク'
      //'user'=>'takuya',
      //'enabled'=>true,
      //'name'=>'sample cron',
      //'comment'=>'sample job'
    ]))->save();
    //$cron_entry = (new CronEntry([
    //  'cron_date'=>'* * * * *',
    //  'command'=>'whoami',
    //  'user'=>'takuya',
    //  //'enabled'=>true,
    //  //'name'=>'sample cron',
    //  //'comment'=>'sample job'
    //]))->save();
    foreach(range(0,3000, 1000) as $int){
      $cron_entry = (new CronEntry([
        'owner_id'=> User::find(1)->id,
        'cron_date'=>'* * * * *',
        'command'=>"sleep ${int}; echo end; ",
        'name'=>"${int}秒タスク"
      ]))->save();
    }
    $cron_entry = (new CronEntry([
  
      'owner_id'=> User::find(1)->id,
      'cron_date'=>'*/15 * * * *',
      'command'=> <<<EOS
        <?php
        sleep(1);
        echo "Hello world\n";
        EOS,
      'user'=>'takuya',
      'shell'=>'php'
    ]))->save();
    $cron_entry = (new CronEntry([
    
      'owner_id'=> User::find(1)->id,
      'cron_date'=>'*/15 * * * *',
      'command'=> <<<'EOS'
        <?php
        exit(1);
        EOS,
      'user'=>'takuya',
      'shell'=>'php',
      'name'=>'いつもエラーになるやつ'
    ]))->save();
    $cron_entry = (new CronEntry([
    
      'owner_id'=> User::find(1)->id,
      'cron_date'=>'*/15 * * * *',
      'command'=> <<<'EOS'
        <?php
        for ( $i=0;$i<30;$i++){
          echo $i.PHP_EOL;
          fwrite(STDERR,'err out'.PHP_EOL);
          usleep(1000*1000);
        }
        EOS,
      'user'=>'takuya',
      'shell'=>'php',
      'name'=>'出力しながらループ'
    ]))->save();
  }
}
