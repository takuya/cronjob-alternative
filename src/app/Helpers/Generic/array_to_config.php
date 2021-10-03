<?php


if ( !function_exists( 'array_to_config' ) ) {
  /**
   * Array to Section ini file
   * https://stackoverflow.com/a/67788916
   * @param array $array
   * @param array $parent
   * @return string
   */
  function array_to_config ( array $array, array $parent = [] ): string {
    $returnValue = '';
    
    foreach ( $array as $key => $value ) {
      if ( is_array( $value ) ) // Subsection case
      {
        // Merge all the sections into one array
        if ( is_int( $key ) ) $key++;
        $subSection = array_merge( $parent, (array)$key );
        // Add section information to the output
        if ( Arr::isAssoc( $value ) ) {
          if ( count( $subSection ) > 1 ) $returnValue .= PHP_EOL;
          $returnValue .= '['.implode( ':', $subSection ).']'.PHP_EOL;
        }
        // Recursively traverse deeper
        $returnValue .= array_to_config( $value, $subSection );
        $returnValue .= PHP_EOL;
      } else if ( isset( $value ) ) $returnValue .= "$key=".( is_bool( $value ) ? var_export( $value, true ) : $value ).PHP_EOL; // Plain key->value case
    }
    return count( $parent ) ? $returnValue : rtrim( $returnValue ).PHP_EOL;
  }
  
}