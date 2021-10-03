<?php


use Carbon\CarbonInterval;


if ( !function_exists( 'date_interval_for_humans_jp' ) ) {
  /**
   * @param float $seconds_interval
   */
  function date_interval_for_humans_jp ( $seconds_interval ) {
    return CarbonInterval::create( '0s' )
                         ->locale( "ja_JP" )
                         ->addSeconds( $seconds_interval )
                         ->cascade()
                         ->forHumans();
  }
}
