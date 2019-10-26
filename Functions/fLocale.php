<?php

function __( $sMessage, $sDomain ) {
	return dgettext( $sDomain, $sMessage );
}

function _e( $sMessage, $sDomain ) {
	echo dgettext( $sDomain, $sMessage );
}