# PHP MailChimp API Class Library

A PHP class library which allows you to add, get, and remove users to MailChimp lists via AJAX POST calls to an API endpoint.

## Usage

Before you start enter your MailChimp API key in the config/config.php file to authorise access.

```
$config = array (
	"mailchimpAPIKey" => "YOUR_API_KEY_GOES_HERE"
);
```

To call the MailChimp API you can make an AJAX POST to the api/mailchimp.php page with the MailChimp list ID, email address, first name (optional), and the call type.

### Example AJAX Call: Add New User

```
// Let's set the call type for the API
let callType = "addUser";

// Let's set the MailChimp List ID to add the user to
let listID = "xxxxxx";

// Let's encode the email address
let encodedEmailAddress	= encodeURIComponent("bob@yoursite.com");

// Let's set the first name (optional)
let firstName = "Bob";

// Let's build the postData string to submit
let postData = "callType=" + callType + "&listID=" + listID + "&emailAddress=" + encodedEmailAddress + "&firstName=" + firstName;

// Let's setup the Ajax call
$.ajax({
	type: "POST",
	url: "api/mailchimp.php",
	data: postData,
	cache: false,
	success: function(result){

		// Let's parse the incoming data
		let data = JSON.parse(result);

		// DID WE GET A SUCCESS MESSAGE?
		if (data["status"] != 200) {

			// There's an error, check the console for details
			console.log("error: ", data);

		}
		// END IF DID WE GET A SUCCESS MESSAGE?

	},
	error: function(e) {
		console.log(e)
	}
});
```

### Example AJAX Call: Get Existing User

```
// Let's set the call type for the API
let callType = "getUser";

// Let's set the MailChimp List ID to add the user to
let listID = "xxxxxx";

// Let's encode the email address
let encodedEmailAddress	= encodeURIComponent("bob@yoursite.com");

// Let's build the postData string to submit
let postData = "callType=" + callType + "&listID=" + listID + "&emailAddress=" + encodedEmailAddress;

// Let's setup the Ajax call
$.ajax({
	type: "POST",
	url: "api/mailchimp.php",
	data: postData,
	cache: false,
	success: function(result){

		// Let's parse the incoming data
		let data = JSON.parse(result);

		// DID WE GET A SUCCESS MESSAGE?
		if (data["status"] != 200) {

			// There's an error, check the console for details
			console.log("error: ", data);

		}
		// END IF DID WE GET A SUCCESS MESSAGE?

	},
	error: function(e) {
		console.log(e)
	}
});
```

### Example AJAX Call: Delete User

```
// Let's set the call type for the API
let callType = "deleteUser";

// Let's set the MailChimp List ID to add the user to
let listID = "xxxxxx";

// Let's encode the email address
let encodedEmailAddress	= encodeURIComponent("bob@yoursite.com");

// Let's build the postData string to submit
let postData = "callType=" + callType + "&listID=" + listID + "&emailAddress=" + encodedEmailAddress;

// Let's setup the Ajax call
$.ajax({
	type: "POST",
	url: "api/mailchimp.php",
	data: postData,
	cache: false,
	success: function(result){

		// Let's parse the incoming data
		let data = JSON.parse(result);

		// DID WE GET A SUCCESS MESSAGE?
		if (data["status"] != 200) {

			// There's an error, check the console for details
			console.log("error: ", data);

		}
		// END IF DID WE GET A SUCCESS MESSAGE?

	},
	error: function(e) {
		console.log(e)
	}
});
```
