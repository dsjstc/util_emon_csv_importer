#!/usr/bin/php
<?php
/* This software copyright 2017 by Thundersun, Inc..
 * You may use it according to the terms of the accompanying license.
 * 
 * This script loops through a CSV file and uploads it to your EmonCMS server.
 */

class Set {
	// DEFAULT VALUES 
	static $InputFile = "input.csv";  // CSV file to parse.
	static $TimeCol=0; // Column offset to date-time.
	static $DataCol=1; // Column offset to meter data.

	// DEFAULT FLAGS
	static $DumpRows=1;  // For debugging, does nothing other than dump this many rows on the console and exit.
}

// MAIN PROGRAM LOGIC
parse_args();
if( Set::$DumpRows ) 
	dump_columns();



// FUNCTIONS
function dump_columns() {
	for( $i=0; $i<=Set::$DumpRows; $i++ ) {
		print "$i\n";
	}
}

function parse_args() {
	global $argv;

	$opts = getopt('a:b:', [], $optind);
	$pos_args = array_slice($argv, $optind);

	// Help.
	if( isset($opts["h"]) ) {
		print "Usages: \n";
		print "emoncsv.php -h\n";
		print "emoncsv.php FILENAME.CSV\n";
		exit();
	}

	// File.
	global $InputFile;
	if( isset($pos_args[0]) ) Set::$InputFile = $pos_args[0];
}

?>
