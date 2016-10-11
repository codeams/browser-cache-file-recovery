<?php

  sleep(2);

  function isIndexValid( $array, $index ) {

    $isIndexSet = isset( $array[ $index ] );
    $isIndexValueNull = is_null( $array[ $index ] );

    $isIndexValid = $isIndexSet && !$isIndexValueNull;

    return $isIndexValid;

  }

  if ( isIndexValid( $_POST, 'cacheString' ) ) {

    $cacheString = $_POST['cacheString'];

  } else die( '<textarea>Not enough parameters to proceed.</textarea>' );

?><textarea><?php

  $hexLines = array();
  preg_match_all('/(\s[0-9a-f]{2}){16}/', $cacheString, $hexLines);

  foreach ( $hexLines[0] as $hexLine ) {

    $hexChars = array();
    preg_match_all('/([0-9a-f]{2})/', $hexLine, $hexChars);

    foreach ( $hexChars[0] as $hexChar ) {

      echo chr( hexdec ( $hexChar ) );

    }

  }

?></textarea>
