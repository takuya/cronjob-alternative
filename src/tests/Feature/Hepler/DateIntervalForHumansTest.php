<?php

namespace Tests\Feature\Hepler;

use Tests\TestCase;

class DateIntervalForHumansTest extends TestCase {
  
  public function test_date_interval_carbon_interval () {
    $asserts = [
      ['30 seconds', 30.179007],
      ['30 seconds', 30.999],
      ['1 minute 8 seconds', 68.12],
      ['1 hour 1 minute 8 seconds', 3668],
      ['1 day 1 hour 1 minute 8 seconds', 3600 * 24 * 1 + 3600 * 1 + 60 + 8],
      ['2 days 1 hour 1 minute 8 seconds', 3600 * 24 * 2 + 3600 * 1 + 60 + 8],
    ];
    
    foreach ( $asserts as $idx => $data )
      $this->assertEquals( $data[0], date_interval_for_humans( $data[1] ), 'en' );
  }
  
  public function test_date_interval_carbon_interval_ja_jp () {
    $asserts = [
      ['30秒', 30.179007],
      ['1分 8秒', 68.12],
      ['1時間 1分 8秒', 3668],
      ['1日 1時間 1分 8秒', 3600 * 24 * 1 + 3600 * 1 + 60 + 8],
    ];
    
    foreach ( $asserts as $idx => $data )
      $this->assertEquals( $data[0], date_interval_for_humans_jp( $data[1] ) );
  }
  
}
