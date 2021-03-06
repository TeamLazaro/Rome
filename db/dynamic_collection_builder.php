<?php

ini_set( 'display_errors', 'On' );
ini_set( 'error_reporting', E_ALL );

date_default_timezone_set( 'Asia/Kolkata' );


// functions to interact with the database
require_once 'lib.php';


function logo ( $thing ) {
	echo '<pre style="white-space: pre-wrap">';
	var_dump( $thing );
	echo '</pre>';
}





/* -----
 Fetch user generated data from a Google Sheet
 ----- */
// I'm hardcoding it for simplicity
$rawSpreadsheetData = [
	[ ],
	[ 'Unit Number', 'Discount', 'Rate per Sqft', 'Built-up Area', 'Floor', 'Block', 'Basic Cost', 'Floor Rise', 'Grand Total' ],
	[ 'A11', '0', '10', '1000', '3', 'A', '10000', '0', '10000' ],
	[ 'E55', '0', '10', '10000', '5', 'E', '100000', '0', '100000' ],
	[ 'B25', '0', '10', '3000', '4', 'B', '30000', '0', '30000' ],
	[ 'E69', '0', '10', '9000', '8', 'E', '90000', '0', '90000' ],
	[ 'A19', '0', '10', '2000', '9', 'A', '20000', '0', '20000' ],
	[ 'D27', '0', '10', '7000', '1', 'D', '70000', '0', '70000' ],
	[ ],
	[ 'C09', '0', '10', '6000', '1', 'C', '60000', '0', '60000' ],
	[ 'B59', '0', '10', '4000', '1', 'B', '40000', '0', '40000' ],
	[ 'C15', '0', '10', '5000', '6', 'C', '50000', '0', '50000' ]
];


// Get database connection
$connection = getDBConnection();
// Initialize the db in case it don't already exist
$connection->exec( file_get_contents( 'setup_db.sql' ) );
// Drop the table if it exists
removeCollection( $connection, 'data' );
// Create the table
createCollectionFromData( $connection, 'data', $rawSpreadsheetData );



/* -----
 Initialize the table
 ----- */
$dataValues = array_values( array_filter( $rawSpreadsheetData ) );
foreach (	$dataValues as $row ) {
	addEntry( $connection, 'data', $row );
}

echo 'dun.zo.';
