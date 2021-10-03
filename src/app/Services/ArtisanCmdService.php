<?php

namespace App\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Console\Kernel;
use Symfony\Component\Console\Output\BufferedOutput;

class ArtisanCmdService {
  
  /**
   * artisan console クラスから、コマンド名 ( make:model ) を返す。
   * @param $class_name
   * @return mixed
   * @throws BindingResolutionException
   */
  public static function getCommandName ( $class_name ) {
    $cmd = app()->make( $class_name );
    $name = ( function() { return $this->getName(); } )->call( $cmd );
    return $name;
  }
}