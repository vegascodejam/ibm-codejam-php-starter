<?php
/*******************************************************************************
 * COPYRIGHT LICENSE: This information contains sample code provided in source 
 * code form. You may copy, modify, and distribute these sample programs in any 
 * form without payment to IBM® for the purposes of developing, using, marketing 
 * or distributing application programs conforming to the application programming 
 * interface for the operating platform for which the sample code is written. 
 * Notwithstanding anything to the contrary, IBM PROVIDES THE SAMPLE SOURCE CODE 
 * ON AN "AS IS" BASIS AND IBM DISCLAIMS ALL WARRANTIES, EXPRESS OR IMPLIED, 
 * INCLUDING, BUT NOT LIMITED TO, ANY IMPLIED WARRANTIES OR CONDITIONS OF 
 * MERCHANTABILITY, SATISFACTORY QUALITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE, 
 * AND ANY WARRANTY OR CONDITION OF NON-INFRINGEMENT. IBM SHALL NOT BE LIABLE 
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL OR CONSEQUENTIAL DAMAGES ARISING 
 * OUT OF THE USE OR OPERATION OF THE SAMPLE SOURCE CODE. IBM HAS NO OBLIGATION 
 * TO PROVIDE MAINTENANCE, SUPPORT, UPDATES, ENHANCEMENTS OR MODIFICATIONS 
 * TO THE SAMPLE SOURCE CODE.
 ******************************************************************************/
/**
 * Receives requests from the CodeRally backend server.
 *
 * Requests will be a POST containing an action parameter corresponding to the
 * name of the event in the CodeRally VehicleListener API.  A data parameter
 * will contain the current game state.
 * 
 * @package CodeRally
 * @version 1.0
 */

namespace CodeRally;

require_once("lib/utils.php");

/* {{{SESSION HANDLING */
session_start();

if (isset($_POST['action'])) {
	$action = $_POST['action'];
	if (isset($_POST['data'])) {
		$data = json_decode($_POST['data']);
	}
} else if (isset($_GET['action'])) {
	$action = $_GET['action'];
	if (isset($_GET['data'])) {
		$data = json_decode($_GET['data']);
	}
} else {
		
}
/* }}} */

/* {{{ Vehicle setup */

if (isset($_SESSION['vehicle'])) {
	$vehicle = $_SESSION['vehicle'];
	$track = $_SESSION['track'];
} else if (isset($data)) {
	$vehicle = new Vehicle($data->vehicle);
	$_SESSION['requestId'] = 0;
}
if (isset($data)) {
	$vehicle->update($data->vehicle);
	if (!isset($track)) {
		$track = new Track($data->track);
	}
	$race = new Race($data->race);

	$myVehicleAI = new MyVehicleAI($vehicle, $track, $race, (isset($data->extra) ? $data->extra : null));
}
/* }}} */


/* {{{ Vehicle response handling */

if ($action == 'clear') {
	session_destroy();
	$response = array('status' => 'cleared');

} else if (method_exists($myVehicleAI, $action)) {
    //if the method being sent exists - tun it
	$response = $myVehicleAI->{$action}();
} else if ($action == "") {
	include('views/index.html');
	exit();
} else {
	$response = array('status'=> 'failure');

}

/* }}} */

/* {{{ Display results as a JSON object */

header('Content-type: application/json');

$response['ts'] = date("YmdHis");
$response['requestId'] = $_SESSION['requestId'];

echo json_encode($response);

$_SESSION['vehicle'] = $vehicle;
$_SESSION['track'] = $track;
$_SESSION['requestId']++;

/* }}} */
