<?php

// This is set up to be installed in a peer folder to tusgi
// This require needs to point to a properly configured tsugi
// instance.  Copy this file to config.php and edit accordingly
// if necessary.

// Important - to communicate to the Tsugi library that we are a 
// standalone application using cookie-based sessions we need this line
// before we include config.php

if ( !defined('COOKIE_SESSION') ) define('COOKIE_SESSION', true);

require_once "../tsugi/config.php";

// It is possible to set this up when it is not running on the same
// server as the Tsugi management console.   In that case, take the 
// config-dist.php from Tsugi and set up the configuration in this folder.
// You will need to manually set up the database tables and do 
// administration tasks manually as well.
// 
// You also will need to include the above COOKIE_SESSION line in that 
// config.php file to tell the Tsugi library that we want to cookie-based
// session.
//
// There is some documentation on this more complex installation setup at
//
// https://github.com/csev/tsugi-php-module/blob/master/README.md
