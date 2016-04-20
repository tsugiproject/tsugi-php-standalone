<?php

require_once "config.php";

use \Tsugi\Core\LTIX;

$LAUNCH = LTIX::session_start();

?>
<html><head><title>Tsugi Sample Standalone Application</title></head>
<body style="font-family: sans-serif;">
<h1>Sample Standalone Application</h1>
<?php
    // Some flash messages...
    if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
    }
    if ( isset($_SESSION["success"]) ) {
        echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
        unset($_SESSION["success"]);
    }
?>
<p>
This is a sample Standalone application that uses Tsugi to add support
for logging in.  Here are some things you can do:
<ul>
<li>Visit this file (index.php) to check the session information and see this cool list</li>
<li>Logout completely (LTI and locally) using <a href="logout.php">logout.php</a>
</li>
<li>Login locally using <a href="login.php">login.php</a></li>
<li>Visit a file that checks for local login and is completely unaware of LTI
<a href="unaware.php">unaware.php</a></li>
<li>Visit a file that expects LTI to be provisions and uses the LTI info to send a grade
<a href="grade.php">grade.php</a></li>
<li>Send an LTI launch to this file (index.php)</li>
<li>Send an LTI launch to <a href="launch.php" target="_blank">launch.php</a> to effect a local login</li>
<?php if ( isset($LAUNCH->user) && !isset($_SESSION['user_email']) ) { ?>
<li>Since you <b>do</b> have LTI launch  in your session and <b>do not</b> 
have local login data in your session, 
you can navigate to <a href="launch.php" target="_blank">launch.php</a> 
to effect a local login using LTI launch data
that is already in your session.</li>
<?php } ?>
</ul>
</p>
<p>
Current situation:
<ul>
<li>Local user email: 
<?= isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '<span style="color:red">Not logged in</span>' ?></li>
<li>LTI user email:
<?= isset($LAUNCH->user) ? $LAUNCH->user->email : '<span style="color:red">No LTI Email Address</span>' ?></li>
<li>LTI user name:
<?= isset($LAUNCH->user) ? $LAUNCH->user->displayname : '<span style="color:red">No LTI Email user name</span>' ?></li>
</ul>

<h2>Dump of the LTI variables and session</h2>

<?php

echo("<pre>\n");
$LAUNCH->var_dump();
echo("</pre>\n");
?>
</body>
</html>
