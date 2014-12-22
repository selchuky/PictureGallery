<?php
//include_once 'dbcon.php';

$root = $_SERVER['DOCUMENT_ROOT'];
$imagePath = 'img';

/**
 * PHP sessions are known not to be secure, therefore it is important
 * not just to put "session_start()" at the top of every page on which
 * you want to use php sessions.
 */
function sec_session_start() {
	// Set a custom session name
	$session_name = 'sec_session_id';
	$secure = SECURE;
	// This stops JavaScript being able to access the session id
	$httponly = true;
	// Forces sessions to only use cookies
	if (ini_set('session.use_only_cookies', 1) === FALSE) {
		header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
		exit();
	}
	// Gets current cookies params
	$cookieParams = session_get_cookie_params();
	session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
	// Sets the session name to the one set above
	session_name($session_name);
	// Start the PHP session
	session_start();
	// regenerated the session, delete the old one
	session_regenerate_id();
}

/**
 * This function checks the email and password against the database.
 * Returns true if there is a match.
 */
function login($email, $password, $mysqli) {
	// Using prepared statements means that SQL injection is not possible
	if ($stmt = $mysqli -> prepare("SELECT id, username, password, salt FROM users WHERE email = ? LIMIT 1")) {
		// Bind "$email" to parameter
		$stmt -> bind_param('s', $email);
		// Execute the prepared query
		$stmt -> execute();
		$stmt -> store_result();

		// get variables from result
		$stmt -> bind_result($user_id, $username, $db_password, $salt);
		$stmt -> fetch();

		// hash the password with the unique salt
		$password = hash('sha512', $password . $salt);
		if ($stmt -> num_rows == 1) {
			// If the user exists we check if the account is locked from too many login attempts
			if (checkbrute($user_id, $mysqli) == true) {
				// TODO: Account is locked, send an email to user saying their account is locked
				return false;
			} else {
				// Check if the password in the database matches the password the user submitted
				if ($db_password == $password) {
					// Password is correct! Get the user-agent string of the user
					$user_browser = $_SERVER['HTTP_USER_AGENT'];
					// XSS protection as we might print this value
					$user_id = preg_replace("/[^0-9]+/", "", $user_id);
					$_SESSION['user_id'] = $user_id;
					// XSS protection as we might print this value
					$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
					$_SESSION['username'] = $username;
					$_SESSION['login_string'] = hash('sha512', $password . $user_browser);
					// Login successful
					return true;
				} else {
					// Password is not correct, record this attempt in the database
					$now = time();
					$mysqli -> query("INSERT INTO login_attempts(user_id, time)
                                      VALUES ('$user_id', '$now')");
					return false;
				}
			}
		} else {
			// No user exists.
			return false;
		}
	}
}

/**
 * If a user account has more than five failed logins, his account
 * is locked.
 */
function checkbrute($user_id, $mysqli) {
	// Get timestamp of current time
	$now = time();

	// All login attempts are counted from the past 2 hours
	$valid_attempts = $now - (2 * 60 * 60);

	if ($stmt = $mysqli -> prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) {
		$stmt -> bind_param('i', $user_id);

		// Execute the prepared query.
		$stmt -> execute();
		$stmt -> store_result();

		// If there have been more than 5 failed logins
		if ($stmt -> num_rows > 5) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Check the "user_id" and "login_string" session variables.
 * "login_string" session variable has the user's browser
 * hashed together with the password.
 */
function login_check($mysqli) {
	// Check if all session variables are set
	if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
		$user_id = $_SESSION['user_id'];
		$login_string = $_SESSION['login_string'];
		$username = $_SESSION['username'];

		// Get the user-agent string of the user
		$user_browser = $_SERVER['HTTP_USER_AGENT'];

		if ($stmt = $mysqli -> prepare("SELECT password FROM members WHERE id = ? LIMIT 1")) {
			// Bind "$user_id" to parameter
			$stmt -> bind_param('i', $user_id);
			// Execute the prepared query
			$stmt -> execute();
			$stmt -> store_result();

			if ($stmt -> num_rows == 1) {
				// If the user exists get variables from result.
				$stmt -> bind_result($password);
				$stmt -> fetch();
				$login_check = hash('sha512', $password . $user_browser);

				if ($login_check == $login_string) {
					// Logged In!!!!
					return true;
				} else {
					// Not logged in
					return false;
				}
			} else {
				// Not logged in
				return false;
			}
		} else {
			// Not logged in
			return false;
		}
	} else {
		// Not logged in
		return false;
	}
}

/**
 * Sanitizes the output from the PHP_SELF server variable. 
 * Trouble with using the server variable unfiltered is that
 * it can be used in a cross site scripting attack. 
 */
function esc_url($url) {

	if ('' == $url) {
		return $url;
	}

	$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

	$strip = array('%0d', '%0a', '%0D', '%0A');
	$url = (string)$url;

	$count = 1;
	while ($count) {
		$url = str_replace($strip, '', $url, $count);
	}

	$url = str_replace(';//', '://', $url);

	$url = htmlentities($url);

	$url = str_replace('&amp;', '&#038;', $url);
	$url = str_replace("'", '&#039;', $url);

	if ($url[0] !== '/') {
		// We're only interested in relative links from $_SERVER['PHP_SELF']
		return '';
	} else {
		return $url;
	}
}

/**
 * This method gets the files from a given directory via the 'path' parameter.
 */
function getImagesFromDir($path) {
	$images = array();
	if ($img_dir = opendir($path)) {
		while (false !== ($img_file = readdir($img_dir))) {
			// Check for gif, jpg, png
			if (preg_match("/(\.gif|\.jpg|\.png)$/", $img_file)) {
				$images[] = $img_file;
			}
		}
		closedir($img_dir);
	}
	return $images;
}
