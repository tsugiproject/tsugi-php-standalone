<?php

// This is an example of some code that just checks 
// the "old logged in" stuff and is completely unaware
// of LTI

session_start();
?>
<html><head><title>Old Code Standalone Application</title></head>
<body style="font-family: sans-serif;">
<h1>Old Code Standalone Application</h1>
<p>
<?php

if ( isset($_SESSION['user_email']) ) {
    echo("Your are logged in as ".$_SESSION['user_email']);
} else {
    echo("You are not logged in");
}
?>
</p>
<p>
....... Lots-o-cool stuff
</p>
<p>
<a href="index.php">Back to the index.php</a>
</body>
</html>
