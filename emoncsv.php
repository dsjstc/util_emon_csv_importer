#!/usr/bin/php
<?php
/* This software copyright 2017 by Thundersun, Inc..
 * You may use it according to the terms of the accompanying license.
 */

main();

function main() {
	parse_args();
	print "main continues\n";
}

function parse_args() {
	$options = getopt("f:hp:");
	
	// Help.
	if( isset($options["h"]) ) {
		print "help\n";
		exit();
	}
}

?>
