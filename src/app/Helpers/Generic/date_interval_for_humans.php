<?php


use Carbon\CarbonInterval;


if ( !function_exists( 'date_interval_for_humans' ) ) {
  /**
   * @param float $seconds_interval
   */
  function date_interval_for_humans ( $seconds_interval, $locale = 'en' ) {
    return CarbonInterval::create( '0' )
                         ->locale( $locale )
                         ->addSeconds( $seconds_interval )
                         ->cascade()
                         ->forHumans();
  }
}
