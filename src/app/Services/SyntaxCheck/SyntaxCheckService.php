<?php


namespace App\Services\SyntaxCheck;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Services\SyntaxCheck\Exceptions\NotSupportedCommandException;
use App\Services\SyntaxCheck\Exceptions\ShellSyntaxError;

class SyntaxCheckService {
  
  /** @var string */
  protected $exception_class;
  /** @var string[] */
  protected $cmd;
  
  public static  $available = [
    'php'=> PHPSyntaxCheckerService::class,
    'bash'=>BashSyntaxCheckerService::class
  ];
  
  public function __construct ( $cmd = null, $exception_class = null ) {
    $this->cmd = $cmd ?? $this->cmd;
    $this->exception_class = $exception_class ?? $this->exception_class;
    //
  }
  public static function supportedList(){
    return static::$available;
  }
  public static function supported($cmd){
    return in_array($cmd,array_keys(static::supportedList()));
  }
  
  /**
   * @param      $src
   * @param null $cmd
   * @throws  NotSupportedCommandException|ShellSyntaxError
   */
  public static function validate ( $src, $cmd = null ) {
    $class = static::class;
    if ( $cmd ){
      if (!static::supported($cmd)){
        throw new NotSupportedCommandException();
      }else{
        $class = static::$available[$cmd];
      }
    }
    /** @var SyntaxCheckService $checker */
    $checker = new $class();
    $checker->check_syntax( $src );
  }
  
  public function check_syntax ( $src ) {
    $proc = new Process( $this->cmd );
    try {
      $proc->setInput( $src );
      $proc->mustRun();
    } catch (ProcessFailedException $ex) {
      throw new $this->exception_class( $proc->getErrorOutput() );
    }
  }
}