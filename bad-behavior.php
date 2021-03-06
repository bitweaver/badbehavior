<?php
global $gBitSystem;
/*
Bad Behavior - detects and blocks unwanted Web accesses
Copyright (C) 2005-2006 Michael Hampton

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

As a special exemption, you may link this program with any of the
programs listed below, regardless of the license terms of those
programs, and distribute the resulting program, without including the
source code for such programs: ExpressionEngine

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

Please report any problems to badbots AT ioerror DOT us
*/

###############################################################################
###############################################################################

define('BB2_CWD', BADBEHAVIOR_PKG_PATH);

// Settings you can adjust for Bad Behavior.
// Most of these are unused in non-database mode.
global $bb2_settings_defaults;
$bb2_settings_defaults = array(
	'log_table' => BIT_DB_PREFIX.'badbehavior',
	'display_stats' => $gBitSystem->getConfig('badbehavior-display-stats', true),
	'strict' => $gBitSystem->getConfig('badbehavior-strict', false),
	'verbose' => $gBitSystem->getConfig('badbehavior-verbose', false)
);

// Bad Behavior callback functions.

// Return current time in the format preferred by your database.
function bb2_db_date() {
	global $gBitSystem;
	return $gBitSystem->mServerTimestamp->getUTCTime();
}

// Return affected rows from most recent query.
function bb2_db_affected_rows() {
	global $gBitSystem;
	return $gBitSystem->mDb->Affected_Rows();
}

// Escape a string for database usage
function bb2_db_escape($string) {
	global $gBitSystem;
	switch ($gBitSystem->mDb->mType) {
	case 'postgres':
		return pg_escape_string($string);
	default:
		return mysql_escape_string($string);
	}
}

// Return the number of rows in a particular query.
function bb2_db_num_rows($result) {
	if( $result )
		return $result->numRows();
	return 0;
}

// Run a query and return the results, if any.
// Should return FALSE if an error occurred.
// Bad Behavior will use the return value here in other callbacks.
function bb2_db_query($query) {
	global $gBitSystem;
	return $gBitSystem->mDb->query($query);
}

// Return all rows in a particular query.
// Should contain an array of all rows generated by calling mysql_fetch_assoc()
// or equivalent and appending the result of each call to an array.
function bb2_db_rows($result) {
	$ret = array();
	if( $result && $result->numRows() ) {
		while ($res = $result->fetchRow() ) {
			$ret[] = $res;
		}
	}
	return $ret;
}

// Return emergency contact email address.
function bb2_email() {
	global $gBitSystem;
	return $gBitSystem->getErrorEmail();
}

// retrieve settings from database
// Settings are hard-coded for non-database use
function bb2_read_settings() {
	global $gBitSystem;
	global $bb2_settings_defaults;
	$config = $gBitSystem->loadConfig('badbehavior');
	foreach ($bb2_settings_defaults as $key => $value) {
		$our_key = 'badbehavior_'.$key;
		if (!empty($config[$our_key])) {
			$bb2_settings_defaultls[$key] = $config[$our_key];
		}
	}
	return $bb2_settings_defaults;
}

// write settings to database
function bb2_write_settings($settings) {
	global $gBitSystem;

	$gBitSystem->StartTrans();
	$gBitSystem->expungePackageConfig('badbehavior');
	foreach ($settings as $key => $val) {
		// Lets avoid changing the table please!
		if ($key != 'log_table') {
			$gBitSystem->storeConfig('badbehavior_'.$key, $val, 'badbehavior');
		}
	}
	$gBitSystem->CompleteTrans();
}

// installation
function bb2_install() {
	return false;
}

// Screener
// Insert this into the <head> section of your HTML through a template call
// or whatever is appropriate. This is optional we'll fall back to cookies
// if you don't use it.
function bb2_insert_head() {
	global $bb2_javascript;
	return $bb2_javascript;
}

// Display stats? This is optional.
function bb2_insert_stats($force = false) {
	$settings = bb2_read_settings();

	if ($force || $settings['display_stats']) {
		$blocked = bb2_db_query("SELECT COUNT(*) FROM " . $settings['log_table'] . " WHERE `key` NOT LIKE '00000000'");
		if ($blocked !== FALSE) {
			$ret = $blocked->fetchRow();
			return $ret['count'];
		}
	}
}

// Return the top-level relative path of wherever we are (for cookies)
// You should provide in $url the top-level URL for your site.
function bb2_relative_path() {
	return BIT_ROOT_URL;
}

// Calls inward to Bad Behavor itself.
require_once(BB2_CWD . "/bad-behavior/version.inc.php");
require_once(BB2_CWD . "/bad-behavior/core.inc.php");
// We install with our own installer.
// bb2_install();	// FIXME: see above
bb2_start(bb2_read_settings());

?>
