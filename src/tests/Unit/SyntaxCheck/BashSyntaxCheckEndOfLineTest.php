<?php

namespace SyntaxCheck;


use PHPUnit\Framework\TestCase;
use App\Services\SyntaxCheck\PHPSyntaxCheckerService;
use App\Services\SyntaxCheck\Exceptions\PHPInvalidSyntaxError;
use App\Services\SyntaxCheck\SyntaxCheckService;
use App\Services\SyntaxCheck\Exceptions\NotSupportedCommandException;
use App\Services\SyntaxCheck\BashSyntaxCheckerService;
use App\Services\SyntaxCheck\Exceptions\BashInvalidSyntaxError;

class BashSyntaxCheckEndOfLineTest extends TestCase {
  
  public function test_bash_syntax_check_ok() {
    $src =<<<'EOS'
      echo a;
      function sample() {
        echo hello;
      }
      if [[ -z $a ]] ; then
        echo empty
      fi
    EOS;
    $src = preg_replace("/\r\n|\r|\n/", "\n", $src, -1 );
    BashSyntaxCheckerService::validate($src);
    $this->assertTrue(true);
  }
  public function test_bash_syntax_check_crlf() {
    $this->expectException( BashInvalidSyntaxError::class );
    $src =<<<'EOS'
      echo a;
      function sample() {
        echo hello;
      }
      if [[ -z $a ]] ; then
        echo empty
      fi
    EOS;
    $src = preg_replace("/\r\n|\r|\n/", "\r\n", $src, -1 );
    BashSyntaxCheckerService::validate($src);
  }
  public function test_bash_syntax_check_cr() {
    $this->expectException( BashInvalidSyntaxError::class );
    $src =<<<'EOS'
      echo a;
      function sample() {
        echo hello;
      }
      if [[ -z $a ]] ; then
        echo empty
      fi
    EOS;
    $src = preg_replace("/\r\n|\r|\n/", "\r", $src, -1 );
    BashSyntaxCheckerService::validate($src);
  }
  
}
