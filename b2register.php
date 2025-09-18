<?php
/* <Register> */

require('b2config.php');
require($abspath.$b2inc.'/b2functions.php');

function add_magic_quotes($array) {
	foreach ($array as $k => $v) {
		if (is_array($v)) {
			$array[$k] = add_magic_quotes($v);
		} else {
			$array[$k] = addslashes($v);
		}
	}
	return $array;
} 

if (!get_magic_quotes_gpc()) {
	$_GET    = add_magic_quotes($_GET);
	$_POST   = add_magic_quotes($_POST);
	$_COOKIE = add_magic_quotes($_COOKIE);
}

$b2varstoreset = array('action');
for ($i=0; $i<count($b2varstoreset); $i += 1) {
	$b2var = $b2varstoreset[$i];
	if (!isset($$b2var)) {
		if (empty($_POST["$b2var"])) {
			if (empty($_GET["$b2var"])) {
				$$b2var = '';
			} else {
				$$b2var = $_GET["$b2var"];
			}
		} else {
			$$b2var = $_POST["$b2var"];
		}
	}
}

if (!$users_can_register) {
	$action = 'disabled';
}

switch($action) {

case "register":

	function filter($value)	{
		return preg_match("^[a-zA-Z0-9\_-\|]+$",$value);
	}

	$user_login = $_POST["user_login"];
	$pass1 = $_POST["pass1"];
	$pass2 = $_POST["pass2"];
	$user_email = $_POST["user_email"];
	$user_login = $_POST["user_login"];

	/* declaring global fonctions */
#	global $user_login,$pass1,$pass2,$user_firstname,$user_nickname,$user_icq,$user_email,$user_url;
		
	/* checking login has been typed */
	if ($user_login=='') {
		die ("<b>ERROR</b>: please enter a Login");
	}

	/* checking the password has been typed twice */
	if ($pass1=='' ||$pass2=='') {
		die ("<b>ERROR</b>: please enter your password twice");
	}

	/* checking the password has been typed twice the same */
	if ($pass1!=$pass2)	{
		die ("<b>ERROR</b>: please type the same password in the two password fields");
	}
	$user_nickname=$user_login;

	/* checking e-mail address */
	if ($user_email=="") {
		die ("<b>ERROR</b>: please type your e-mail address");
	} else if (!is_email($user_email)) {
		die ("<b>ERROR</b>: the email address isn't correct");
	}

	$id=mysqli_connect($server,$loginsql,$passsql);
	if ($id==false)	{
		die ("<b>OOPS</b>: can't connect to the server !".mysqli_error());
	}

	mysqli_select_db( $wpdb->dbh,"$base") or die ("<b>OOPS</b>: can't select the database $base : ".mysqli_error());

	/* checking the login isn't already used by another user */
	$request =  " SELECT user_login FROM $tableusers WHERE user_login = '$user_login'";
	$result = mysqli_query( $wpdb->dbh,$request) or die ("<b>OOPS</b>: can't check the login...");
	$lines = mysqli_num_rows($result);
	mysqli_free_result($result);
	if ($lines>=1) {
		die ("<b>ERROR</b>: this login is already registered, please choose another one");
	}

	$user_ip = $_SERVER['REMOTE_ADDR'] ;
	$user_domain = gethostbyaddr($_SERVER['REMOTE_ADDR'] );
	$user_browser = $_SERVER['HTTP_USER_AGENT'];

	$user_login=addslashes($user_login);
	$pass1=addslashes($pass1);
	$user_nickname=addslashes($user_nickname);

	$query = "INSERT INTO $tableusers (user_login, user_pass, user_nickname, user_email, user_ip, user_domain, user_browser, dateYMDhour, user_level, user_idmode) VALUES ('$user_login','$pass1','$user_nickname','$user_email','$user_ip','$user_domain','$user_browser',NOW(),'$new_users_can_blog','nickname')";
	$result = mysqli_query( $wpdb->dbh,$query);
	if ($result==false) {
		die ("<b>ERROR</b>: couldn't register you... please contact the <a href=\"mailto:$admin_email\">webmaster</a> !".mysqli_error());
	}

	$stars="";
	for ($i = 0; $i < strlen($pass1); $i = $i + 1) {
		$stars .= "*";
	}

	$message  = "new user registration on your blog $blogname:\r\n\r\n";
	$message .= "login: $user_login\r\n\r\ne-mail: $user_email";

	@mail($admin_email,"new user registration on your blog $blogname",$message);

	?><html>
<head>
<title>b2 > Registration complete</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $siteurl; ?>/wp-admin/b2.css" type="text/css" />
</head>
<body>
<div id="login"> 
	<h2>Registration Complete</h2>
	<p>Login: <strong><?php echo $user_login; ?></strong><br />
	Password: <strong><?php echo $stars; ?></strong><br />
	E-mail: <strong><?php echo $user_email; ?></strong></p>
	<form action="b2login.php" method="post" name="login">
		<input type="hidden" name="log" value="<?php echo $user_login; ?>" />
		<input type="submit" value="Login" name="submit" />
	</form>
</div>
</body>
</html>

	<?php
break;

case "disabled":

	?><html>
<head>
<title>b2 > Registration Currently Disabled</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $siteurl; ?>/wp-admin/b2.css" type="text/css" />
</head>
<body>
<div id="login">
	<h2>Registration Disabled</h2>
	<p>User registration is currently not allowed.<br />
	<a href="<?php echo $siteurl.'/'.$blogfilename; ?>" title="Go back to the blog">Home</a>
	</p>
</div>
</body>
</html>

	<?php
break;

default:

	?><html>
<head>
<title>b2 > Register form</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $siteurl; ?>/wp-admin/b2.css" type="text/css" />
</head>
<body>
<div id="login">
<h2>Registration</h2>
<form method="post" action="b2register.php">
	<input type="hidden" name="action" value="register" />
	<label for="user_login">Login:</label> <input type="text" name="user_login" id="user_login" size="10" maxlength="20" /><br />
	<label for="pass1">Password:</label> <input type="password" name="pass1" id="pass1" size="10" maxlength="100" /><br />
 
	<input type="password" name="pass2" size="10" maxlength="100" /><br />
	<label for="user_email">E-mail</label>: <input type="text" name="user_email" id="user_email" size="15" maxlength="100" /><br />
	<input type="submit" value="OK" class="search" name="submit" />
</form>
</div>
</body>
</html>
	<?php

break;
}