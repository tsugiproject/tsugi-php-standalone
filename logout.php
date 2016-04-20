<?php

// This wipes out the whole session whether it be a local
// login or LTI launch - all gone.

// We do save and restore the Tsugi Developer Console login
// so a full page refresh does not fail when we are in an 
// iframe and sharing a session cookie with the Dev console 
// in the outer frame - that would not be necessary in a real
// standalone application.

session_start();

// Save the Tsugi Developer Console login if present
$dev = isset($_SESSION['developer']);

session_unset();

// Restore the Tsugi Developer Console login if present
if ( $dev ) $_SESSION['developer'] = 'yes';

// Give us a little indication when we redirect :)
$_SESSION["success"] = "Logged out.";
header("Location: index.php");
