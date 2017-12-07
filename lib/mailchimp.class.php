<?php

	// Turn on error reporting
	error_reporting(E_STRICT);

	class MailChimp
	{

		private $config;

		public function __construct($config) {
			
			// Constructor function to get and set the config details for the API
			$this->config = $config;

		}

		public function addUser($newUserDetails) {

			// This function adds a new user to a specified MailChimp list
			// It requires a single array to be passed in which contains the following:
			// 		$newUserDetails["listID"]		= the MailChimp List ID to add the new user to
			// 		$newUserDetails["emailAddress"]	= the new user's email address
			// 		$newUserDetails["firstName"]	= (Optional) the new user's first name, not used on every list

			// Let's add the required action to the array for the API call
			$newUserDetails["action"] = "addUser";

			// Let's make the API call
			$response = $this->callMailChimp($newUserDetails);

			// Let's send back the response
			return $response;

		}

		public function getUser($userDetails) {

			// This function gets a user from a specified MailChimp list
			// It requires a single array to be passed in which contains the following:
			// 		$userDetails["listID"]			= the MailChimp List ID to access
			// 		$userDetails["emailAddress"]	= the user's email address

			// Let's add the required action to the array for the API call
			$userDetails["action"] = "getUser";

			// Let's make the API call
			$response = $this->callMailChimp($userDetails);

			// Let's send back the response
			return $response;

		}

		public function deleteUser($userDetails) {

			// This function deletes a user from a specified MailChimp list
			// It requires a single array to be passed in which contains the following:
			// 		$userDetails["listID"]			= the MailChimp List ID to delete the user from
			// 		$userDetails["emailAddress"]	= the user's email address

			// Let's add the required action to the array for the API call
			$userDetails["action"] = "deleteUser";

			// Let's make the API call
			$response = $this->callMailChimp($userDetails);

			// Let's send back the response
			return $response;

		}

		private function callMailChimp($updateData) {

			// This function makes the call to the MailChimp API and performs the required action
			// It requires a single array to be passed in which contains the details of the required action, list and user data
			// The $updateData array is comprised of the following items:
			//		$updateData["action"]		= "addUser" | "deleteUser" | "getUser"
			//		$updateData["listID"]		= the MailChimp List ID to access
			//		$updateData["emailAddress"]	= the user's email address
			//		$updateData["firstName"]	= (Optional) the user's first name, won't be on every list

			// Build the curl header authorisation
			$header		= array();
			$header[]	= "Content-type: application/json";
			$header[]	= "Authorization: apikey " . $this->config["mailchimpAPIKey"];

			// Let's setup our standard variables which we'll need in the calls
			$lowercaseEmail	= strtolower($updateData["emailAddress"]);
			$userHash		= md5($lowercaseEmail);
			$apiUrl			= "https://us10.api.mailchimp.com/3.0/lists/" . $updateData["listID"] . "/members/";

			// WHAT TYPE OF CALL ARE WE DOING?
			if ($updateData["action"] == "getUser") {

				// We're getting a user so let's add the user's hash to the apiUrl and set the $callType
				$apiUrl		= $apiUrl . $userHash;
				$callType	= "GET";

			} else if ($updateData["action"] == "addUser") {

				// We're adding a new user, so let's add their data into the $newUserDetails array
				$newUserDetails = array(
					"email_address"	=> $lowercaseEmail,
					"status"		=> "subscribed"
				);

				// DO WE HAVE A FIRST NAME FOR THE USER?
				if ($updateData["firstName"]) {

					// Yes we do, so let's add the first name into the $newUserDetails array
					$newUserDetails["merge_fields"]["FNAME"] = $updateData["firstName"];

				}
				// END IF DO WE HAVE A FIRST NAME FOR THE USER?

				// Let's json encode the array for the API POST
				$newUserDetails = json_encode($newUserDetails);

				// Let's set the $callType
				$callType	= "POST";

			} else if ($updateData["action"] == "deleteUser") {

				// We're deleting a user, so let's add the user's hash to the apiUrl and set the $callType
				$apiUrl		= $apiUrl . $userHash;
				$callType	= "DELETE";

			}
			// END IF WHAT TYPE OF CALL ARE WE DOING?

			// Build the curl command and set the options
			$ch = curl_init($apiUrl);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $callType);

			// ARE WE DOING A POST AND NEED TO ADD SOME POST FIELDS?
			if ($updateData["action"] == "addUser") {

				// Yes we are, so let's add the post field with the $newUserDetails array
				curl_setopt($ch, CURLOPT_POSTFIELDS, $newUserDetails);
			
			}
			// END IF ARE WE DOING A POST AND NEED TO ADD SOME POST FIELDS?

			// Run the curl and contact the API
			$result = @curl_exec($ch);

			// Decode the reply from MailChimp
			$reply = json_decode($result, true);

			// Return the decoded reply
			return $reply;

		}

	}

?>