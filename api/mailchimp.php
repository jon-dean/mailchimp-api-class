<?php

	// Turn on error reporting
	error_reporting(E_STRICT);

	// Let's set the root path so we can load the scripts and files properly
	$rootPath = $_SERVER["DOCUMENT_ROOT"];
	
	// Include the Config file and the MailChimp Class library
	include($rootPath . "/config/config.php");
	require_once($rootPath . "/lib/mailchimp.class.php");

	// Let's create a new MailChimp class object
	$mc = new MailChimp($config);

	// DO WE HAVE AN INCOMING CALL?
	if (isset($_POST["callType"])) {

		// Let's add the incoming data to an array for processing
		$userData["listID"]			= $_POST["listID"];
		$userData["emailAddress"]	= $_POST["emailAddress"];

		// IS THE FIRST NAME SET?
		if (isset($_POST["firstName"])) {

			// Yes it is, so let's add it to the array
			$userData["firstName"] = $_POST["firstName"];

		}
		// END IF IS THE FIRST NAME SET?

		// WHAT CALL TYPE ARE WE PROCESSING?
		if ($_POST["callType"] == "addUser") {

			// Let's call the MailChimp API
			$addUser = callAddUser($mc, $userData);

			// Let's echo the response for the calling AJAX to process
			echo $addUser;

		} else if ($_POST["callType"] == "getUser") {

			// Let's call the MailChimp API
			$getUser = callGetUser($mc, $userData);

			// Let's echo the response for the calling AJAX to process
			echo $getUser;

		} else if ($_POST["callType"] == "deleteUser") {

			// Let's call the MailChimp API
			$deleteUser = callDeleteUser($mc, $userData);

			// Let's echo the response for the calling AJAX to process
			echo $deleteUser;

		} else {

			// Incoming callType is empty, return an error
			$response = json_encode(array("status" => 404, "message" => "Access Denied"));
			echo $response;

		}
		// END IF WHAT CALL TYPE ARE WE PROCESSING?

	} else {

		// No incoming callType, return an error
		$response = json_encode(array("status" => 404, "message" => "Access Denied"));
		echo $response;

	}
	// END IF DO WE HAVE AN INCOMING CALL?



//--------------------------------------------------------------------
function callAddUser($mc, $userData) {
//--------------------------------------------------------------------

	// This function calls the MailChimp->addUser() function to add a new user
	// to a specified MailChimp list and handles any errors that are returned

	// Let's call the MailChimp API to try an add the new user
	$addUser = $mc->addUser($userData);

	// DO WE HAVE A SUCCESS RESPONSE?
	if ($addUser["status"] == 400) {

		// We have a 400 response, so the user is already on the MailChimp list
		$response = json_encode(array(
			"status"		=> 400,
			"message"		=> "User already exists"
		));

		// Let's send back the encoded response
		return $response;

	} else {

		// We have a success response, let's process their data and return the response
		$response = json_encode(array(
			"status"		=> 200,
			"message"		=> "Success",
			"emailAddress"	=> $addUser["email_address"],
			"md5"			=> $addUser["merge_fields"]["MD5"]
		));

		// Let's send back the encoded response
		return $response;

	}

}

//--------------------------------------------------------------------
function callGetUser($mc, $userData) {
//--------------------------------------------------------------------

	// This function calls the MailChimp->getUser() function to get an existing user
	// from a specified MailChimp list and handles any errors that are returned

	// Let's call the MailChimp API to get the user
	$getUser = $mc->getUser($userData);

	// DID WE GET A USER
	if ($getUser["status"] == 404) {

		// The user isn't on the list, let's send back an error message
		$response = json_encode(array(
			"status" => 404,
			"message" => "Can't Process User, Please Try Again."
		));

		// Let's send back the encoded response
		return $response;

	} else {

		// We have a user, let's process their data and return the response
		$response = json_encode(array(
			"status"		=> 200,
			"message"		=> "Success",
			"emailAddress"	=> $getUser["email_address"],
			"md5"			=> $getUser["merge_fields"]["MD5"]
		));

		// Let's send back the encoded response
		return $response;

	}
	// END IF DID WE GET A USER?

}


//--------------------------------------------------------------------
function callDeleteUser($mc, $userData) {
//--------------------------------------------------------------------

	// This function calls the MailChimp->deleteUser() function to remove an existing user
	// from a specified MailChimp list and handles any errors that are returned

	// Let's call the MailChimp API to delete the user
	$deleteUser = $mc->deleteUser($userData);

	// Response is either 404 if the user doesn't exist or an empty response if they were deleted
	// So either way let's send back a success message as they've been removed either way
	$response = json_encode(array(
		"status"	=> 200,
		"message"	=> "User Deleted"
	));

	// Let's send back the encoded response
	return $response;

}

?>