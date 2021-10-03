<?php

namespace App\Services\SyntaxCheck;

use Symfony\Component\Process\ExecutableFinder;
use App\Services\SyntaxCheck\Exceptions\ShellCommandNotFoundException;


class CommandExistsCheckerService {
  
  
  public static function validate ( $cmd ) {
    $executableFinder = new ExecutableFinder();
    if ( $executableFinder->find( $cmd ) == null ) {
      throw new ShellCommandNotFoundException( "Not found '{$cmd}'." );
    }
  }
}