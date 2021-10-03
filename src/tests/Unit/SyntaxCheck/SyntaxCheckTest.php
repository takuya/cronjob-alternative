<?php

namespace SyntaxCheck;


use PHPUnit\Framework\TestCase;
use App\Services\SyntaxCheck\PHPSyntaxCheckerService;
use App\Services\SyntaxCheck\Exceptions\PHPInvalidSyntaxError;
use App\Services\SyntaxCheck\SyntaxCheckService;
use App\Services\SyntaxCheck\Exceptions\NotSupportedCommandException;

class SyntaxCheckTest extends TestCase {
  
  public function test_syntax_check_invalid_syntax () {
    $this->expectException( PHPInvalidSyntaxError::class );
    SyntaxCheckService::validate( '<?php a.aaa', 'php' );
  }
  
  public function test_syntax_check_not_supported () {
    $this->expectException( NotSupportedCommandException::class );
    SyntaxCheckService::validate( 'grep', 'aaaa' );
  }
  
  public function test_syntax_check_php () {
    PHPSyntaxCheckerService::validate( '<? echo $a;' );
    $this->expectNotToPerformAssertions();
  }
  
  public function test_syntax_invalid_php () {
    $this->expectException( PHPInvalidSyntaxError::class );
    PHPSyntaxCheckerService::validate( '<?php echo $a a' );
  }
  
}
