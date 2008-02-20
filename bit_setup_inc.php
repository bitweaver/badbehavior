<?php
global $gBitSystem, $gBitSmarty;

$registerHash = array(
	'package_name' => 'badbehavior',
	'package_path' => dirname( __FILE__ ).'/',
	'service' => 'badbehavior',
	);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'badbehavior' ) ) {

	require_once(BADBEHAVIOR_PKG_PATH.'bad-behavior.php');

}

?>