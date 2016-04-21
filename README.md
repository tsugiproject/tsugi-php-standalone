
TSUGI PHP Standalone Sample
===========================

There are two ways to use the Tsugi library/framework:

* You can use Tsugi like a library and add it to a few places in 
an existing application.  This repository contains 
sample code showing how to use Tsugi as a library in an 
existing application.

* You can also build a "Tsugi Module" from scratch following all of the
Tsugi style guidance, using the Tsugi browser environment, and
making full use of the Tsugi framework.
We also have starting code for
[Bulding a Tsugi Module](https://github.com/csev/tsugi-php-module)

Both of these approaches depend on the
[Tsugi Devloper/Admin Console](https://github.com/csev/tsugi)
for database configuration, setup, developer test harness,
CASA support, Content Item Support, etc.

Setup/Configuration
-------------------

You need to download and install the 
[Tsugi Developer/Administrator Console](https://github.com/csev/tsugi)
to be able to setup database tables, test your software, and configure 
your keys.   Once things are setup - end-users or teachers do not need 
access to the Tsugi application unless yuo want to let them apply for 
keys.

Once that is installed, you can download this software:

    https://github.com/csev/tsugi-php-standalone

The software comes with a `config.php` that assumes it this program is
installed in the same htdocs folder as the Tsugi Console.  This is a 
quick way to get this program up and running for testing and exploration.
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

If for production purposes, you need to run your application on a server
without installing the Tsugi management console, see the more advanced
configuration instructions below.

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

* If the request is not an LTI launch (or a GET after LTI Launch POST), it looks in 
the session to see if there is LTI data in the session and populates the $LAUNCH object 
with as much of the User, Context, Link, and Result information as it can find.

Your code must not assume that these values are always set since there might be
more than one way to enter the application.  So code that might send a grade back 
needs to protect itself and only call routines if sufficient LTI data is present.

    if ( isset($LAUNCH->result) ) {
        $LAUNCH->result->gradeSend(0.95);
    }

Limitations of Cookie-Based Sessions
------------------------------------

In standalone model, we will use cookies to manage the sessions.   Using cookies
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
sessions in normal embeddable Tsugi Modules.  But since there are so many
existing applications that need an LTI integreation that cannot be rewritten,
we accept these limitations in our Tsugi standalone approach.

Virtually all of the older LTI integrations based on `lti_util.php` or a similar
pattern have these exact same limitations since they use cookie-based sessions.

Tsugi Developer List
--------------------

Once you start developing Tsugi Applications or Modules, you should join the Tsugi
Developers list so you can get announcements when things change.

    https://groups.google.com/a/apereo.org/forum/#!forum/tsugi-dev

Once you have joined, you can send mail to tsugi-dev@apereo.org

Advanced Installation
---------------------

If you are going to install this tool in a web server that does not
already have an installed copy of the Tsugi management console,
it is a bit trickier.  There is no automatic connection between Tsugi developer 
tools and Tsugi admin tools won't know about this tool.   
But it can run stand alone.

First install composer to include dependencies.

    http://getcomposer.org/

I just do this in the folder:

    curl -O https://getcomposer.org/composer.phar

Get a copy of the latest `composer.json` file from the 
[Tsugi repository](https://github.com/csev/tsugi)
or a recent Tsugi installation and copy it into this folder.

To install the dependencies into the `vendor` area, do:

    php composer.phar install

If you want to upgrade dependencies (perhaps after a `git pull`) do:

    php composer.phar update

Note that the `composer.lock` file and `vendor` folder are 
both in the `.gitignore` file and so they won't be checked into
any repo.

For advanced configuation, you need to retrieve a copy of 
`config-dist.php` from the 
[Tsugi repository](https://github.com/csev/tsugi)
or a copy of `config.php`
from a Tsugi install and place the file in this folder.

Then you will need to configure the database connection, etc for this
appication by editing `config.php`.  

A key element of the configuration is to include this line as part
of the configuration to indicate to the Tsugi run-time that we 
are using cookie-based sessions.

    if ( !defined('COOKIE_SESSION') ) define('COOKIE_SESSION', true);

The `config-dist.php` has a configuration line commented out to 
serve as an example.

Running (Advanced Configuration)
--------------------------------

Once it is installed and configured, you can do an LTI launch to

    http://localhost:8888/tsugi-php-standalone/index.php
    key: 12345
    secret: secret

You can use your Tsugi installation or my test harness at:

    https://online.dr-chuck.com/sakai-api-test/lms.php

And it should work!

Upgrading the Library Code (Advanced Configuration)
---------------------------------------------------

From time to time the library code in

    https://github.com/csev/tsugi-php

Will be upgraded and pulled into Packagist:

    https://packagist.org/packages/tsugi/lib

To get the latest version from Packagist, edit `composer.json` and
update the commit hash to the latest hash on the `packagist.org` site
and run:

    php composer.phar update


