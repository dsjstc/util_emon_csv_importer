#!/usr/bin/php
<?php
/* This software copyright 2017 by Thundersun, Inc..
 * You may use it according to the terms of the accompanying license.
 * 
 * This script loops through a CSV file and uploads it to your EmonCMS server.
 */
// An assortment of defaults.
$host_em = new Host([
	'url'=>'http://em/emoncms/input/post.json', 
	'api'=>'eb1934dd930439f892fd446e8944b46e', 
	'uspw'=>'atat:bb'
	]);

G::$host = $host_em;

G::$settings = new Settings([
	// Actions
	'Flooper' =>6, // Makes the values be a multiple of 10 kW
	// Settings
	'InputFile' => "m30.csv",
	'Serial' => "001EC6000783",
	'SubDevice' => "mb250",
	'TimeCol' =>0,
	'DataCol' =>4,
	'NodeNum' =>1
	]);


// MAIN PROGRAM LOGIC
parse_args();
if( G::$settings->DumpRows ) 
	dump_columns();

if( G::$settings->Flooper ) 
	flooper();


// FUNCTIONS
function flooper() {
	$i=0;
	$total = 0;
	while( true ) {
		$timestamp = time();
		$add = rand(1,9);
		$total += $add;
		$bulkData = '['.$timestamp.','.G::$settings->NodeNum.','.$total.']';
		sendPoint($total);
		//print "sending: ".$bulkData."\r\n";
		sleep(G::$settings->Flooper);
		$i++;
		//if( $i > 1 ) break;
	}
}

function sendPoint($totalCons) {
	$emoncms_url =  G::$host->url;
	$emoncms_api = G::$host->api;
	$nodeNum = G::$settings->NodeNum;

	$time_now = time();
	$sendTo = $emoncms_url."?time=$time_now"."&apikey=".$emoncms_api."&node=".$nodeNum;
	$wholeUrl=$sendTo."&csv=".$totalCons;
	print $wholeUrl. "\n";

	$cur_opts = [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FAILONERROR => true,
		CURLOPT_URL => $wholeUrl,
		CURLOPT_SAFE_UPLOAD => true];
	curl_setopt_array($ch = curl_init(), $cur_opts);
	
	if( isset(G::$host->uspw) )
		curl_setopt($ch, CURLOPT_USERPWD, G::$host->uspw);

	$response = curl_exec($ch);
	$rcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	print "($rcode)---"; 
	print $response;
	print "===\r\n";
}

function dump_columns() {
	$file = fopen(G::$settings->InputFile, 'r');
	for( $i=0; $i<=G::$settings->DumpRows; $i++ ) {
		$line = trim(fgets($file));
	  $expl = explode(',', $line);
	  if( sizeof($expl) < 2 || strlen($line) == 0 ) {
	  	print('bad line (' . strlen($line) . "/" . sizeof($expl) . '): ' . $line . "\n");
	  	continue;
	  }
	  $data = '['.$expl[G::$settings->TimeCol].','.G::$settings->NodeNum.','.$expl[G::$settings->DataCol].'],';  
	  print( "$data\n" );
	}
}

function parse_args() {
	global $argv;

	$opts = getopt('h', [], $optind);
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
	if( isset($pos_args[0]) ) G::$settings->InputFile = $pos_args[0];
}


// CLASSES
class G {
	// Holder for global statics (for concise global access)
	public static $settings;
	public static $host;
}

class Settings {
	public function __construct($row){
	 foreach ($row as $prop=>$value){
		if( property_exists(get_class(), $prop) ) {
			$this->$prop=$value;
		}
		else {
			print("Bad property: ".$prop."\n");
			print_r( debug_backtrace() );
		}
	 }
	}	

	// DEFAULT VALUES 
	public $InputFile;  // CSV file to parse.
	public $Serial; 	// Acquisition device
	public $SubDevice;	// Acquisition sub-device	  
	public $TimeCol; 	// Column offset to date-time.
	public $DataCol; 	// Column offset to meter data.
	public $NodeNum;

	// ACTION FLAGS
	public $DumpRows;  	// Dump this many rows on the console and exit. [debugging]
	public $Flooper;	// Every $Flooper seconds, uploads a random value 1-10.
}

class Host {
	public function __construct($row){
	 foreach ($row as $prop=>$value){
		if( property_exists(get_class(), $prop) ) {
			$this->$prop=$value;
		}
		else {
			print("Bad property: ".$prop."\n");
			print_r( debug_backtrace() );
		}
	 }
	}	
	
	public $url;
	public $api;
	public $uspw;
}

?>
