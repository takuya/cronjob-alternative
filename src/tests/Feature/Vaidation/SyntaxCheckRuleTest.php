<?php

namespace Tests\Feature\Vaidation;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Rules\ShellInputRule;

class SyntaxCheckRuleTest extends TestCase {
  
  public function test_pass_shell_syntax_has_no_error () {
    $validator = Validator::make(
      ['shell' => ['cmd' => 'bash', 'body' => 'echo Hello;',],],
      ['shell' => ['required', new ShellInputRule()],]
    );
    $ret = $validator->passes();
    $this->assertTrue( $ret );
  }
  
  public function test_catch_shell_syntax_has_error () {
    $validator = Validator::make(
      ['shell' => ['cmd' => 'bash', 'body' => 'if aaa (())fi',],],
      ['shell' => ['required', new ShellInputRule()],]
    );
    $ret = $validator->passes();
    $this->assertFalse( $ret );
  }
}
