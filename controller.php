<?php

require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set( 'Asia/Kolkata' );

function logo ( $thing ) {
	echo '<pre style="white-space: pre-wrap">';
	var_dump( $thing );
	echo '</pre>';
}

define( 'SPREADSHEET_ID', '1LupSf0NLpR4Qtw-Nwpok0NCR7BcZWGME1AjOxPf1WGU' );
define( 'SHEET_RANGE', 'output!A1:B' );

define( 'APPLICATION_NAME', 'This is a Test' );
define( 'CREDENTIALS_PATH', '~/.credentials/sheets.googleapis.com-php-quickstart.json' );
define( 'CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json' );
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/sheets.googleapis.com-php-quickstart.json
define( 'SCOPES', implode( ' ', [ Google_Service_Sheets::SPREADSHEETS_READONLY ] ) );

// only permit this to run from the command line
// if ( php_sapi_name() != 'cli' ) {
	// throw new Exception('This application must be run on the command line.');
// }


/*
 * Imports the output / contents of a PHP script
 * in a way such that it can be assigned to variable
 */
function require_to_var ( $__file__, $ctx ) {
	extract( $ctx );
	ob_start();
	require $__file__;
	return ob_get_clean();
}


/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient () {
	$client = new Google_Client();
	$client->setApplicationName( APPLICATION_NAME );
	$client->setScopes( SCOPES );
	$client->setAuthConfig( CLIENT_SECRET_PATH );
	$client->setAccessType( 'offline' );

	// Load previously authorized credentials from a file.
	$credentialsPath = expandHomeDirectory( CREDENTIALS_PATH );
	if ( file_exists( $credentialsPath ) ) {
		$accessToken = json_decode(file_get_contents($credentialsPath), true);
	} else {
		// Request authorization from the user.
		$authUrl = $client->createAuthUrl();
		printf( "Open the following link in your browser:\n%s\n", $authUrl );
		print 'Enter verification code: ';
		$authCode = trim( fgets( STDIN ) );

		// Exchange authorization code for an access token.
		$accessToken = $client->fetchAccessTokenWithAuthCode( $authCode );

		// Store the credentials to disk.
		if ( ! file_exists( dirname( $credentialsPath ) ) ) {
			mkdir( dirname( $credentialsPath ), 0700, true );
		}
		file_put_contents( $credentialsPath, json_encode( $accessToken ) );
		printf( "Credentials saved to %s\n", $credentialsPath );
	}

	$client->setAccessToken( $accessToken );

	// Refresh the token if it's expired.
	if ( $client->isAccessTokenExpired() ) {
		$client->fetchAccessTokenWithRefreshToken( $client->getRefreshToken() );
		file_put_contents( $credentialsPath, json_encode( $client->getAccessToken() ) );
	}
	return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory ( $path ) {
	$homeDirectory = getenv( 'HOME' );
	if ( empty( $homeDirectory ) ) {
		$homeDirectory = getenv( 'HOMEDRIVE' ) . getenv( 'HOMEPATH' );
	}
	return str_replace( '~', realpath( $homeDirectory ), $path );
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Sheets($client);

// $spreadsheetId = '1LupSf0NLpR4Qtw-Nwpok0NCR7BcZWGME1AjOxPf1WGU';
// $range = 'Sheet1!A1:J';
$response = $service->spreadsheets_values->get( SPREADSHEET_ID, SHEET_RANGE );
$key_value_pairs = $response->getValues();

// logo( $key_value_pairs );

$template_data = [ ];

foreach ( $key_value_pairs as $pair ) {
	$template_data[ $pair[ 0 ] ] = $pair[ 1 ];
}

// logo( $template_data );

$markup = require_to_var( 'template.php', $template_data );

echo $markup;
