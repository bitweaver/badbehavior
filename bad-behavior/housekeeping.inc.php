<?php if (!defined('BB2_CORE')) die('I said no cheating!');

function bb2_housekeeping($settings, $package)
{
	// FIXME Yes, the interval's hard coded (again) for now.
	$query = "DELETE FROM `" . $settings['log_table'] . "` WHERE `date` < " . (bb2_db_date() - (60 * 60 * 24 * 7));
	bb2_db_query($query);

	// Waste a bunch more of the spammer's time, sometimes.
	if (rand(1,1000) == 1) {
		sleep(10);
	}
}

?>
