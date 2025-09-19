# WordPress 0.71-gold modified to run under PHP 8

This is a fork of WordPress 0.71-gold ( released 9th June 2003 ) which has been modified to run under PHP 8.

mysql> create database b2_20250917;

Modify b2config.php as follows:

// ** MySQL settings **

// The name of the database  
define('DB_NAME', 'b2_20250917');  
// Your MySQL username  
define('DB_USER', 'root');  
// ...and password  
define('DB_PASSWORD', '123456');  
// 99% chance you won't need to change this value  
define('DB_HOST', 'localhost');

Launch wp-install.php in your browser: http://localhost/wp-admin/wp-install.php

Go to b2login.php and sign in with the login "admin" and the password, then click on the menu 'My Profile', and change the password.

topwaye@hotmail.com
