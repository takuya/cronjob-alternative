<?php

namespace Tests\Feature\Vaidation;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Rules\CronExpressionRule;

class CronExpressionValidationTest extends TestCase {
  
  public function test_cron_expression () {
    $validator = Validator::make( ['cron_string' => '* * * * */2'], [
      'cron_string' => ['required', new CronExpressionRule()],
    ] );
    $ret = $validator->passes();
    $this->assertTrue( $ret );
  }
  
  public function test_cron_expression_catch_invalid () {
    $validator = Validator::make( ['cron_string' => 'aa* * * * */2'], [
      'cron_string' => ['required', new CronExpressionRule()],
    ] );
    $ret = $validator->passes();
    $this->assertFalse( $ret );
  }
}
