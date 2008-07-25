<?php
global $gBitSystem, $gBitSmarty, $gShellScript;

$registerHash = array(
	'package_name' => 'badbehavior',
	'package_path' => dirname( __FILE__ ).'/',
	'service' => 'badbehavior',
	);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'badbehavior' ) && ( empty($gShellScript) || $gShellScript == FALSE ) ) {

	require_once(BADBEHAVIOR_PKG_PATH.'bad-behavior.php');

}

?>