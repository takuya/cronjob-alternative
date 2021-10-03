<?php

if ( !function_exists( 'get_classname_base' ) ) {
  function get_classname_base ( $classname ) {
    return substr( strrchr( $classname, "\\" ), 1 );
  }
}

