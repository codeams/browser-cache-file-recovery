<?php

  # Note: This will not print spaces and line-breaks
  # print it inside a textarea if you need those.

  # Paste in the cacheString value the
  # cache text you copied from your browser.
  $cacheString = '';

  $hexLines = array();
  preg_match_all('/(\s[0-9a-f]{2}){16}/', $cacheString, $hexLines);

  foreach ( $hexLines[0] as $hexLine ) {

    $hexChars = array();
    preg_match_all('/([0-9a-f]{2})/', $hexLine, $hexChars);

    foreach ( $hexChars[0] as $hexChar ) {

      echo chr( hexdec ( $hexChar ) );

    }

  }

?>
