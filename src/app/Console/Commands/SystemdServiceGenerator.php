<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\ExecutableFinder;
use Arr;

class SystemdServiceGenerator extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'schedule:systemd_generate  {output=php://stdout} {--php=7.4}';
  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Generate a systemd service unit file.';
  
  public function handle () {
    $ini = array_to_config( $this->genConfig() );
    file_put_contents( $this->argument( 'output' ), $ini );
    return 0;
  }
  
  protected function genConfig () {
    $finder = new ExecutableFinder();
    $env = $finder->find( 'env' ) ?? '/usr/bin/env';
    
    
    $php = "php{$this->option('php')}";
    $php = $finder->find( $php ) ?? "{$env} {$php}";
    
    
    $config = [];
    $config['Unit'] = [];
    $config['Unit']['Description'] = config( 'app.name' ).'/ laravel schedule worker';
    $config['Unit']['Wants'] = 'multi-user.target';
    $config['Unit']['After'] = 'multi-user.target';
    $config['Service'] = [];
    $config['Service']['WorkingDirectory'] = base_path( '' );
    #$config['Service']['ExecStartPre'] = "${php} composer.phar install";
    $config['Service']['ExecStart'] = "${php} artisan cron:work";
    $config['Service']['Type'] = 'simple';
    $config['Service']['StandardOutput'] = 'journal';
    $config['Service']['StandardError'] = 'journal';
    $config['Service']['Restart'] = 'on-failure';
    $config['Service']['User'] = 'root';
    $config['Service']['Group'] = 'www-data';
    $config['Install'] = [];
    $config['Install']['WantedBy'] = 'multi-user.target';
    
    return $config;
  }
}
