# WordPress 0.71-gold modified to run under PHP 8

This is a fork of WordPress 0.71-gold ( released 9th June 2003 ) which has been modified to run under PHP 8.

mysql> create database b2_20250917;

Modify b2config.php as follows:
```
// $siteurl is your blog's URL (no trailing slash !)

$siteurl = 'http://localhost';      // Double check this, it's very important

// ** MySQL settings **

define('DB_NAME', 'b2_20250917');   // The name of the database
define('DB_USER', 'root');          // Your MySQL username
define('DB_PASSWORD', '123456');    // ...and password
define('DB_HOST', 'localhost');     // 99% chance you won't need to change this value
```
Launch wp-install.php in your browser: http://localhost/wp-admin/wp-install.php

Go to b2login.php and sign in with the login "admin" and the password, then click on the menu 'My Profile', and change the password.

## Debugging PHP with VSCode and XDebug

### Configure PHP
Edit your php.ini file and add the following configuration:
```
[xdebug]
zend_extension=c:/php8/ext/php_xdebug-3.4.5-8.4-ts-vs17-x86_64.dll
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_port=9003
xdebug.client_host=localhost
```
Ensure your Apache service is restarted for any changes made in php.ini to take effect.

### Configure VSCode PHP
Drop down the menu bar in VSCode: File > Perferences > Settings > Extensions > PHP  
Click on edit in settings.json. Add the following configuration:
```
{
    "workbench.colorTheme": "Default Light Modern",
    "php.validate.executablePath": "c:/php8/php.exe"
}
```
### Configure VSCode Debugger
Open your project in VSCode.  
Go to the Run and Debug panel (Ctrl+Shift+D).  
Click on create a launch.json file. It should create default configuration as the following with port 9003.
```
{
    "name": "Listen for Xdebug",
    "type": "php",
    "request": "launch",
    "port": 9003
}
```
Do NOT touch it.

### Run and Debug
Open any PHP file.  
Click in the left margin next to a line number or press F9 to set a breakpoint.  
Run the debugger (F5) and **execute the PHP script in your browser**.  
The script execution will pause at the breakpoint.

topwaye@hotmail.com
