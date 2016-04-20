<?php

// This is part of your application that wants to be LTI aware
// and send a grade back to the LMS.

require_once "config.php";

use \Tsugi\Core\LTIX;

$LAUNCH = LTIX::session_start();

if ( !isset($LAUNCH->result) ) {
    $_SESSION['error'] = "We have no result so we can't send a grade";
    header("Location: index.php");
    return;
}

// Not much error checking here
if ( isset($_POST['grade']) )  {

    $gradetosend = $_POST['grade']+0.0;
    $retval = $LAUNCH->result->gradeSend($gradetosend);

    if ( $retval === true ) {
        $_SESSION['success'] = "Grade $gradetosend sent to server.";
    } else {
        $_SESSION['error'] = "Grade not sent: ".$retval;
    }
    header("Location: index.php");
    return;
}

?>
<html><head><title>Tsugi Sample Standalone Grader</title></head>
<body style="font-family: sans-serif;">
<h1>Tsugi Sample Standalone Grader</h1>
<p>
<form method="post">
Enter grade:
<input type="number" name="grade" step="0.01" min="0" max="1.0"><br/>
<input type="submit" name="send" value="Send grade">
</form>
</p>
<p>
<a href="index.php">Back to the index.php</a>
</p>
</body>
</html>

