<?php

  # Note:
  # If you paste the $originalText inside a html
  # document you won't be able to see line-breaks
  # or spaces, if you need those you should print
  # it inside a <textarea>

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

?>
