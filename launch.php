<?php

// This is an example of an endpoint that receives an LTI launch
// using Tsugi and sets up the session to effect a local login.  

require_once "config.php";

use \Tsugi\Core\LTIX;

$LAUNCH = LTIX::session_start();

if ( !isset($LAUNCH->user) ) {
    die("This path needs to be launched using LTI");
}

if ( !isset($LAUNCH->user->email) ) {
    die("This LTI tool requires email");
}

// Log the user in locally - note local session already started
$_SESSION["user_email"] = $LAUNCH->user->email;
$_SESSION["success"] = "Logged in.";
header( 'Location: index.php' ) ;
