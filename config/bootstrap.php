<?php
//
// Bootstraps the application environment.
//
// This file is used to initialize the PHP environment for the services,
// the unit and functional tests as well as for Phing tasks.
//

/**
 * Error reporting is turned on in general, but some parts of the framework do
 * not yet conform to E_STRICT.
 */

error_reporting(E_ALL & ~E_STRICT);

/**
 * The default time zone is set to UTC to have consistent time and date
 * handling independent of the time zone setting of the server this
 * application is deployed to.
 */

date_default_timezone_set('UTC');

/**
 * A constant that refers to the root directory of the application. This
 * is useful when there is a need to compose a path to a particular file
 * or directory within the project.
 */

define('ROOT_DIR', dirname(__DIR__));

/**
 * A constant that refers to the environment that the application is running
 * in. This variable determines the configuration file to use.
 */

// The variable is in $_SERVER if it was set by the Apache SetEnv directive.
if (isset($_SERVER['WIRED_DELTA_ENVIRONMENT']))
{
    define('WIRED_DELTA_ENVIRONMENT', $_SERVER['WIRED_DELTA_ENVIRONMENT']);
}

// The variable is in $_ENV if it was set on the command line or in a crontab.
else if (isset($_ENV['WIRED_DELTA_ENVIRONMENT']))
{
    define('WIRED_DELTA_ENVIRONMENT', $_ENV['WIRED_DELTA_ENVIRONMENT']);
}
// If the variable is not set, this is the default value.
else
{
    define('WIRED_DELTA_ENVIRONMENT', 'development');
}

/**
 * Initializes the class autoloader stack.
 */

require __DIR__ . '/../includes/defines.php';

require __DIR__ . '/../includes/functions.php';

require __DIR__ . '/../src/classes/Forms/FormCreator.php';

require __DIR__ . '/../src/classes/Forms/FormValidation.php';
