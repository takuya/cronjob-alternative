<?php

namespace Tests\Feature\Vaidation;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Rules\CronExpressionRule;
use App\Rules\ShellSupported;

class SyntaxSupportedRuleTest extends TestCase {
  
  public function test_pass_shell_supported () {
    $validator = Validator::make( ['shell' => 'bash'], [
      'shell' => ['required', new ShellSupported()],
    ] );
    $ret = $validator->passes();
    $this->assertTrue( $ret );
  }
  
  public function test_catch_shell_not_supported () {
    $validator = Validator::make( ['shell' => 'aaaaaaaaaa'], [
      'shell' => ['required', new ShellSupported()],
    ] );
    $ret = $validator->passes();
    $this->assertFalse( $ret );
  }
}
