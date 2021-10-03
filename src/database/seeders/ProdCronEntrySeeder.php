<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CronEntry;
use App\Models\User;

class ProdCronEntrySeeder extends Seeder {
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
      'name'=>'sample 65 sec sleep',
      'enabled'=>false,
    ]))->save();
    $cron_entry = (new CronEntry([
  
      'name'=>'sample php',
      'owner_id'=> User::find(1)->id,
      'cron_date'=>'*/15 * * * *',
      'command'=> <<<EOS
        <?php
        sleep(1);
        echo "Hello world\n";
        EOS,
      'shell'=>'php'
    ]))->save();
    $cron_entry = (new CronEntry([
      'owner_id'=> User::find(1)->id,
      'cron_date'=>'*/15 * * * *',
      'command'=> <<<'EOS'
        <?php
        exit(1);
        EOS,
      'shell'=>'php',
      'name'=>'always error sample.'
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
      'shell'=>'php',
      'name'=>'出力しながらループ'
    ]))->save();
  }
}
