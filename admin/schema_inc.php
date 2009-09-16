<?php

$tables = array(
	// The auto on this sucks but it is a library so I am ignoring it.
	'badbehavior' => '
		`id` I4 AUTO PRIMARY,
		`ip` C(15) NOT NULL,
		`date` I4 NOT NULL default 0,
		`request_method` X NOT NULL,
		`request_uri` X NOT NULL,
		`server_protocol` X NOT NULL,
		`http_headers` X NOT NULL,
		`user_agent` X NOT NULL,
		`request_entity` X NOT NULL,
		`key` X NOT NULL
	',
	);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
    $gBitInstaller->registerSchemaTable( BADBEHAVIOR_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( BADBEHAVIOR_PKG_NAME, array(
		 'description' => "<a href=\"http://www.bad-behavior.ioerror.us/\">Bad Behavior</a> analyzes how people are using the site in order to try to prevent spammers from posting.",
		'license' => '<a href="http://www.gnu.org/licenses/licenses.html#GPL">GPL</a>',
		) );

$indices = array (
	'ip_idx' => array( 'table' => 'badbehavior', 'cols' => 'ip', 'opts' => NULL ),
	);
$gBitInstaller->registerSchemaIndexes( LIBERTY_PKG_NAME, $indices );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( BADBEHAVIOR_PKG_NAME, array(
		array('p_badbehavior_admin', 'Can admin bad behavior', 'admin', BADBEHAVIOR_PKG_NAME ),
		));

?>
