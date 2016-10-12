<?php

/**
 * Project: Browser cache file recovery
 * Github project: https://github.com/codeams/browser-cache-file-recovery/
 * License: MIT
 *
 * (c)2016 Alejandro Montanez
 */

  function isIndexValid( $array, $index ) {

    $isIndexSet = isset( $array[ $index ] );

    if ( $isIndexSet ) $isIndexValueNotNull = ! is_null( $array[ $index ] );
    else return false;

    if ( $isIndexValueNotNull ) return true;
    else return false;

  }

  function extractHexLines( $string ) {

    $hexLines = [];
    $regex = '/(\s[0-9a-f]{2}){16}/';

    preg_match_all( $regex, $string, $hexLines );

    return $hexLines[0];

  }

  function extractHexCharacters( $string ) {

    $hexCharacters = [];
    $regex = '/([0-9a-f]{2})/';

    preg_match_all( $regex, $string, $hexCharacters );

    return $hexCharacters[0];

  }

  function convertHexToCharacter( $hexCharacter ) {

    $character = chr( hexdec( $hexCharacter ) );
    return $character;

  }

  function getOriginalText( $cacheFileContent ) {

    $originalText = '';

    $hexLines = extractHexLines( $cacheFileContent );

    foreach ( $hexLines as $hexLine ) {

      $hexCharacters = extractHexCharacters( $hexLine );

      foreach ( $hexCharacters as $hexCharacter ) {

        $character = convertHexToCharacter( $hexCharacter );
        $originalText .= $character;

      }

    }

    return $originalText;

  }

  /* -- Run the process -- */

  $isReceivedCacheFileContent = isIndexValid( $_POST, 'cacheFileContent' );

  if ( $isReceivedCacheFileContent ) {

    $cacheFileContent = $_POST['cacheFileContent'];
    $originalText = getOriginalText( $cacheFileContent );

    echo '<textarea>';
    echo $originalText;
    echo '</textarea>';

  } else {

    echo '<textarea>';
    echo 'Not enough parameters to run the script.';
    echo '</textarea>';

  }
