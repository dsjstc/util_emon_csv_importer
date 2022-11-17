# emoncsv
CSV importer for EmonCMS energy logger
- bulk upload CSV data to EmonCMS.
- loop upload consumption data for testing.

Warning: not tested much.  Not tested *at all* with emoncms.org. 

	Usage: 
	emoncsv.php -h
	emoncsv.php [options] FILENAME.CSV

	Actions:
	  -d - dump rows
	  -f - send random consumption
	  -s - send bulk data to server
	  -p - print bulk data that would be sent to server
	  -c - create empty input for the current node (you need to establish feeds before uploading)
	  -h - print this help

	Flags:
	  -v - extra console output, sometimes.
	  -o - format console output with newlines
	  -i - dump all specified settings and exit
	  
	Settings:
	  -gX - load specified instead of settings.php (not implemented)
	  -xN - upload no more than N rows at a time
	  -rX - set data source's serial number to X (does nothing at present) 
	  -eX - set data source subdevice to X (does nothing at present) 
	  -tN - N is 0-base offset to column with human-readable time data in UTC
	  -dN - N is 0-base offset to numeric data value
	  -nN - upload to EmonCMS node number N
	  -mN - stop processing after N input rows
