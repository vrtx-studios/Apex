<?php

function createAttributes( $aAttributes = array() ) {
    $sOutput = '';
    foreach( $aAttributes as $key => $value ) {
        if( $value === null || is_array($value) ) continue;
        $sOutput .= ' ' . $key . '="' . $value . '"';
    }
    return $sOutput;
}

function strToUrl( $sStr ) {
    $sStr = str_replace( array(' ', '\\', '_', '–', '—', '‒'), '-', $sStr );
    $sStr = str_replace( array('&', '?', '#', '%', '+', '$', ',', ':', ';', '=', '@', '&amp;', '<', '>', '{', '}', '|', '^', '~', '[', ']', '`', "'", '"', '!', '¨', '¤'), '', $sStr );

    return mb_strtolower( $sStr );
}