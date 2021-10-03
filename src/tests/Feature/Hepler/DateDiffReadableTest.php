<?php

namespace Hepler;

use Tests\TestCase;


class DateDiffReadableTest extends TestCase {
  /**
   * A basic unit test example.
   *
   * @return void
   */
  public function test_date_diff_for_humans_jp () {
    // 端数切捨ての繰り下げされるのと、
    // new Carbon 実行中に時間経過する ので１秒少なく出ます。
    $asserts = [
      ['+1min +10sec', '1分後'],
      ['+1min', '59秒後'],
      ['+11sec', '10秒後'],
      ['+1 hour', '59分後'],
      ['+1 hour +10sec', '1時間後'],
      ['+1 day', '23時間後'],
      ['+1 day +10sec', '1日後'],
      ['+1 week', '6日後'],
      ['+1 week +10sec', '1週間後'],
      ['next month ', '4週間後'],
      ['next month +3day', '1ヶ月後'],
      ['+3 month +2day ', '3ヶ月後'],
      ['+12 month +2day ', '1年後'],
      ['+24 month +2day ', '2年後'],
    ];
    
    foreach ( $asserts as $data ) {
      $result = date_diff_for_humans_jp( $data[0] );
      $this->assertEquals( $data[1], $result );
    }
  }
}
