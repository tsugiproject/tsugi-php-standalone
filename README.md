# tsugi-php-standalone

TSUGI PHP Standalone Sample
===========================

Tsugi can either be used to build new high functioning LTI applications, 
or it can be used to augment an existing PHP application, using the
Tsugi library to function as a single-sign-on (SSO) for the application,
perhaps in addition to other login processes or SSO processes.

Setup/Configuration
-------------------

You need to download and install the main Tsugi application to be able to setup
database tables, test your software, and configure your keys.   Once things are
setup - end-users or teachers do not need access to the Tsugi application.

    https://github.com/csev/tsugi

Once that is installed, you can download this software:

    https://github.com/csev/tsugi-php-standalone

You must pull in the Tsugi PHP library using composer:

    http://getcomposer.org/

I just do this in the folder:

    curl -O https://getcomposer.org/composer.phar

To install the dependencies into the `vendor` area, do:

    php composer.phar install

If you want to upgrade dependencies (perhaps after a `git pull`) do:

    php composer.phar update

Note that the `composer.lock` file and `vendor` folder are
both in the `.gitignore` file and so they won'g be checked into
any repo.

Then you will need to configure the database connection, etc for this
appication buy creating `config.php`.  If this is running on the same
PHP server as the Tsugi management console (/tsugi) - you can simply
copy `config-dist.php` to `config.php`.  This file has instructions
if you want to run the Tsugi management console on some other server.

A key element of the configuration is to include this line as part
of the configuration to indicate to Tsugi that we are using
cookie-based sessions.

    if ( !defined('COOKIE_SESSION') ) define('COOKIE_SESSION', true);

If you have the Tsugi management console running on the same server,
you can make it so developer mode can test this application by updating
the tool folder list:

    $CFG->tool_folders = array("admin", "samples", ... ,
        "exercises", "../tsugi-php-standalone");

With that the tool will be easily testable from the Tsugi management
console.

How Tsugi Uses Session
----------------------

if you look at `index.php`, you will see these three lines:

    require_once "config.php";
    use \Tsugi\Core\LTIX;

    $LAUNCH = LTIX::session_start();

This functions as an LTI-aware `session_start()` and can be a drect replacement
for `session_start()` in any of your PHP files.  In addition to starting the 
session the LTIX version of `session_start()` does the following:

* Intercepts any LTI launch POSTs, validates them, updates the `lti_` database tables,
adds some LTI data to the session, and then redirects to a GET of the same URL.

* If the request is not an LTI launch (or a GET after LTI Launch POST), it look in 
the session to see if there is LTI data in the session and populates the $LAUNCH object 
with as much of the User, Context, Link, and Result information as it can find.

Any code must not assume that these values are always set since there might be
more than one way to enter the application.  So code that might send a grade back 
needs to protect itself and only call routines if sufficient LTI data is present.

    if ( isset($LAUNCH->result) ) {
        $LAUNCH->result->gradeSend(0.95);
    }

Limitations of Cookie-Based Sessions
------------------------------------

In standlone model, we will use cookies to manage the sessions.   Using cookies
limits the ability to embed the application in an iframe across two domains.
It also means that a single PHPSESSID value will exist for all non-icognito
windows and so if you do a launch on one tab as one user from a course
and then do another launch in a different tab as a different user from a different
course, the login settings will be changed in the first tab since they are 
sharing a PHP session.

This also means that these applications should be launched from the LMS in 
a new window and not hosted in an iframe.

The ablility to have multiple simultaneous sessions and work seamlessly in an 
iframe is one of the reasons that a lot of effort goes into using non-cookie
sessions in normal embeddable Tsugi applications.  But since there are so many
existing applications that need an LTI integreation that cannot be rewritten,
we accept these limitations in our Tsugi standalone approach.

Virtually all of the older LTI integrations based on `lti_util.php` or a similar
pattern have these exact same limitations since they use cookie-based sessions.
