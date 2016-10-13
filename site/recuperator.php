<?php

/**
 * Project: Browser cache file recovery
 * Github project: https://github.com/codeams/browser-cache-file-recovery/
 * License: MIT
 *
 * (c)2016 Alejandro Montañez
 */

  function isIndexValid( $array, $index ) {

    $isIndexSet = isset( $array[ $index ] );

    if ( $isIndexSet ) $isIndexValueNotNull = ! is_null( $array[ $index ] );
    else return false;

    if ( $isIndexValueNotNull ) return true;
    else return false;

  }

  function getAmountSubstringMatches( $string, $substring ) {

    $amountMatches = substr_count( $string, $substring );
    return $amountMatches;

  }

  function removeSubstringBeforeMatch( $string, $substring, $includeSubstring ) {

    $isSubstringInString = strpos( $string, $substring );

    if ( $isSubstringInString ) {

      $stringLength = strlen( $string );
      $removeBefore = $isSubstringInString;
      if ( $includeSubstring ) $removeBefore += strlen( $substring );

      $resultString = substr( $string, $removeBefore, $stringLength );
      return $resultString;

    } else return $string;

  }

  function removeSubstringAfterMatch( $string, $substring, $includeSubstring ) {

    $isSubstringInString = strpos( $string, $substring );

    if ( $isSubstringInString ) {

      $removeAfter = $isSubstringInString;
      if ( $includeSubstring ) $removeAfter -= strlen( $substring );

      $resultString = substr( $string, 0, $removeAfter );
      return $resultString;

    } else return $string;

  }

  function extractHexLines( $string ) {

    # This regex works with chrome but doesn't width firefox:
    #'/(\s[0-9a-f]{2}){16}/'

    $hexLines = array();
    $regex = '/([a-z0-9]{2}( ){1,2}){16}/';

    preg_match_all( $regex, $string, $hexLines );

    return $hexLines[0];

  }

  function extractHexCharacters( $string ) {

    $hexCharacters = array();
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

  function convertCacheFileToOriginalText( $cacheFileContent ) {

    $sectionOpener = '00000000:';
    $amountSectionOpeners = getAmountSubstringMatches( $cacheFileContent, $sectionOpener );

    $hasntHeaderNorFooter = $amountSectionOpeners <= 1;
    $hasHeaderOnly = $amountSectionOpeners === 2;
    $hasHeaderAndFooter = $amountSectionOpeners === 3;
    $isUnknownType = $amountSectionOpeners > 3;

    if ( $hasntHeaderNorFooter ) {

      $originalText = getOriginalText( $cacheFileContent );

    } else if ( $hasHeaderOnly ) {

      # Removes content before HEX header
      $cacheFileContent = removeSubstringBeforeMatch( $cacheFileContent, $sectionOpener, true );

      # Removes content before HEX file content
      $cacheFileContent = removeSubstringBeforeMatch( $cacheFileContent, $sectionOpener, true );

      $originalText = getOriginalText( $cacheFileContent );

    } else if ( $hasHeaderAndFooter ) {

      # Removes content before HEX header
      $cacheFileContent = removeSubstringBeforeMatch( $cacheFileContent, $sectionOpener, true );

      # Removes content before HEX file content
      $cacheFileContent = removeSubstringBeforeMatch( $cacheFileContent, $sectionOpener, true );

      # Removes content after HEX file content
      $cacheFileContent = removeSubstringAfterMatch( $cacheFileContent, $sectionOpener, true );

      $originalText = getOriginalText( $cacheFileContent );

    } else if ( $isUnknownType ) {

      $originalText = "Unknown file type. Please copy only the file content section (the second one)\n";
      $originalText .= 'Detailed instructions: https://github.com/codeams/browser-cache-file-recovery/';

    } else {

      $originalText = 'Unknow error.';

    }

    return $originalText;
  }


  ### Run the process ###

  $isReceivedCacheFileContent = isIndexValid( $_POST, 'cacheFileContent' );

  if ( $isReceivedCacheFileContent ) {

    $cacheFileContent = $_POST['cacheFileContent'];
    $originalText = convertCacheFileToOriginalText( $cacheFileContent );

    echo '<textarea>';
    echo $originalText;
    echo '</textarea>';

  } else {

    echo '<textarea>';
    echo "Not enough parameters to run the script.\n";
    echo 'This is likely to be a client-server communication error.';
    echo '</textarea>';

  }
