<?php
/* Copyright 2017 by Thundersun, Inc.
 * You may use it according to the terms of the accompanying license.
 * 
 * This file holds default settings, should you want to run emoncsv.php
 * without specifying everything on the commandline.
 */

$host_em = new Host([
	'url'=>'http://localhost/emoncms/', 
	'api'=>'YOUR_LOCAL_KEY_HERE', 
	'uspw'=>'username:password' ]); // Should it be necessary.

$host_emoncmsorg = new Host([
	'url'=>'http://emoncms.org/emoncms/', 
	'api'=>'YOUR_KEY_HERE' ]);

G::$host = $host_em;
G::$settings = new Settings([
	// Actions
	//'flooper' =>6,		// Send random consumption, 6 seconds between sends.
	//'dumpRows' => TRUE, 	// dump minimally parsed input data.
	//'sendBulk' => TRUE,	// Send bulk to G::$host
	'printBulk' => TRUE,	// Dry run.
	
	// Settings
	'InputFile' => "example.csv",
	'Serial' => "123456789",		// uniquely identify the source of this data (later use)
	'SubDevice' => "mb250",			// in case you need to further specify the data source.
	'TimeCol' =>0,					// Which column of the source data has the datestamp
	'DataCol' =>4,					// Which column has the upload data
	'NodeNum' =>2,					// Which EMONCMS node to upload to.
	'chunkSize' => 4,				// How many rows to upload to Emon at one time
	'maxRows' =>0 , 				// Stop after this many input rows (0 == MAX_INT)
	'verbose' => TRUE				// Extra console output
	]);
