<?php

/*
 *
 * This script demos append a row to a Google Sheet
 *
 *
 */

ini_set( 'display_errors', 'On' );
ini_set( 'error_reporting', E_ALL );

date_default_timezone_set( 'Asia/Kolkata' );


require_once __DIR__ . '/../vendor/autoload.php';
// functions to setup the Google API Client
require_once '../Google Sheets/lib.php';


function logo ( $thing ) {
	echo '<pre style="white-space: pre-wrap">';
	var_dump( $thing );
	echo '</pre>';
}





/* -----
 * Declaring the data and places on the spreadsheet that are going to be accessed
 ----- */
define( 'SPREADSHEET_ID', $_GET[ 'spreadsheetId' ] ?? '1LupSf0NLpR4Qtw-Nwpok0NCR7BcZWGME1AjOxPf1WGU' );
define( 'APPEND_RANGE', $_GET[ 'appendRange' ] ?? 'API Test Append' );
$requestBody = new Google_Service_Sheets_ValueRange( [
	'range' => APPEND_RANGE,
	'majorDimension' => 'ROWS',
	'values' => [ 'values' => [ 'who', 'dis', 1, 'miss', 'poop', 'apple' ] ]
] );

define( 'APPLICATION_NAME', 'This is a Test' );
define( 'CREDENTIALS_PATH', '~/.credentials/sheets.googleapis.com-php-quickstart.json' );
define( 'CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json' );
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/sheets.googleapis.com-php-quickstart.json
define( 'SCOPES', implode( ' ', [ Google_Service_Sheets::SPREADSHEETS ] ) );

// only permit this to run from the command line
// if ( php_sapi_name() != 'cli' ) {
	// throw new Exception('This application must be run on the command line.');
// }



// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Sheets( $client );

// $spreadsheetId = '1LupSf0NLpR4Qtw-Nwpok0NCR7BcZWGME1AjOxPf1WGU';
// $range = 'Sheet1!A1:J';
$response = $service->spreadsheets_values->append(
	SPREADSHEET_ID,
	APPEND_RANGE,
	$requestBody,
	[
		'valueInputOption' => 'USER_ENTERED'
	]
);

logo( $response->getUpdates()->getUpdatedRange() );
