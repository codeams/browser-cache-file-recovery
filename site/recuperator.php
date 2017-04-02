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

    $matches = array();
    $regex = '/[0-9a-f]{8}:( ){0,2}([0-9a-f]{2}( ){1,2}){1,16}/';

    preg_match_all( $regex, $string, $matches );

    $indexedHexLines = $matches[0];

    $hexLines = array();

    foreach ( $indexedHexLines as $indexedHexLine ) {

      $hexLine = split( ': ', $indexedHexLine )[1];

      array_push( $hexLines, $hexLine );

    }

    return $hexLines;

  }

  function extractHexCharacters( $string ) {

    $matches = array();
    $regex = '/([0-9a-f]{2})/';

    preg_match_all( $regex, $string, $matches );

    $hexCharacters = $matches[0];

    return $hexCharacters;

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
      $cacheFileContent = removeSubstringBeforeMatch( $cacheFileContent, $sectionOpener, false );

      $originalText = getOriginalText( $cacheFileContent );

    } else if ( $hasHeaderAndFooter ) {

      # Removes content before HEX header
      $cacheFileContent = removeSubstringBeforeMatch( $cacheFileContent, $sectionOpener, true );

      # Removes content before HEX file content
      $cacheFileContent = removeSubstringBeforeMatch( $cacheFileContent, $sectionOpener, false );

      # Removes content after HEX file content
      $cacheFileContent = removeSubstringAfterMatch( $cacheFileContent, $sectionOpener, false );

      $originalText = getOriginalText( $cacheFileContent );

    } else if ( $isUnknownType ) {

      $originalText = "Unknown file type. Please copy only the file content section (the second one)\n";
      $originalText .= 'Detailed instructions: https://github.com/codeams/browser-cache-file-recovery/';

    } else {

      $originalText = 'Unknow error.';

    }

    return $originalText;
  }

  function getFileName( $cacheFileContent ) {

    $matches = array();
    $regex = '/(http|https|ftp){1}:\/\/.+/';

    preg_match_all( $regex, $cacheFileContent, $matches );

    $urlMatches = $matches[0];
    $firstUrlMatch = $urlMatches[0];

    $fileName = basename( $firstUrlMatch );
    $fileName = trim( $fileName );

    return $fileName;

  }

  function getContentType( $cacheFileContent ) {

    $matches = array();
    $regex = '/([Cc]{1}ontent-[Tt]ype:).+/';

    preg_match_all( $regex, $cacheFileContent, $matches );

    $contentTypeSentences = $matches[0];
    $contentTypeSentence = $contentTypeSentences[0];

    $contentType = split( ':', $contentTypeSentence )[1];
    $contentType = trim( $contentType );

    return $contentType;

  }

  function getContentClasification( $contentType ) {

    $contentClasification = split( '/', $contentType )[0];
    $contentClasification = trim( $contentClasification );

    return $contentClasification;

  }

  function isGziped( $cacheFileContent ) {

    $isGziped;

    $matches = array();
    $regex = '/([Cc]{1}ontent-[Ee]{1}ncoding:).+/';

    preg_match_all( $regex, $cacheFileContent, $matches );

    $contentEncodingSentences = $matches[0];
    $amountContentEncodingSentences = count( $contentEncodingSentences );

    if ( $amountContentEncodingSentences < 1 ) {

      return false;

    } else {

      $contentEncodingSentence = $contentEncodingSentences[0];

      $contentEncoding = split( ':', $contentEncodingSentence )[1];
      $contentEncoding = trim( $contentEncoding );

      $isGziped = $contentEncoding === 'gzip';

      return $isGziped;

    }

  }


  ### Run the process ###

  $isReceivedCacheFileContent = isIndexValid( $_POST, 'cacheFileContent' );

  if ( $isReceivedCacheFileContent ) {

    $cacheFileContent = $_POST['cacheFileContent'];
    $originalText = convertCacheFileToOriginalText( $cacheFileContent );

    $contentType = getContentType( $cacheFileContent );
    $contentClasification = getContentClasification( $contentType );

    $isGziped = isGziped( $cacheFileContent );
    $isImage = $contentClasification === 'image';

    echo $contentClasification;

    $doesRequireEncode = $isGziped || $isImage;

    if ( $doesRequireEncode ) {

      $base64EncodedOriginalText = base64_encode( $originalText );
      $dataURI = "data:$contentType;base64,$base64EncodedOriginalText";

      $fileName = getFileName( $cacheFileContent );

      if ( $isGziped ) $fileName .= '.gz';

      if ( $isImage ) {

        echo "<a href='$dataURI' download='$fileName'>";
          echo "<img src='$dataURI' alt='$fileName'>";
        echo "</a>";

      } elseif ( $isGziped ) {

        echo "<a href='$dataURI' download='$fileName'>";
          echo 'Download compressed file';
        echo "</a>";

      } else {

        echo '<textarea>';
        echo "Requires encode but it's not an image nor a gziped file.\n";
        echo '</textarea>';

      }

    } else {

      echo '<textarea>';
      echo $originalText;
      echo '</textarea>';

    }

  } else {

    echo '<textarea>';
    echo "Not enough parameters to run the script.\n";
    echo 'This is likely to be a client-server communication error.';
    echo '</textarea>';

  }
