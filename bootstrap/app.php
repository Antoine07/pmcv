<?php
/*
|--------------------------------------------------------------------------
|   Init App
|--------------------------------------------------------------------------
*/
if (getEnv('APP_ENV') == 'dev') {
    ini_set('display_errors', 'On');
}

ini_set('date.timezone', 'Europe/Paris');

/*
|--------------------------------------------------------------------------
|   Constants App
|--------------------------------------------------------------------------
*/

define('URL_SITE', 'http://pmvc.conf');
define('APP_PATH', dirname(__DIR__));

/*
|--------------------------------------------------------------------------
| Dependencies
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Doctrine\Common\Inflector\Inflector;

$dotenv = new Dotenv(__DIR__ . '/..');
$dotenv->load();

/*
|--------------------------------------------------------------------------
| translate file
|--------------------------------------------------------------------------
*/
$lang = require_once __DIR__ . '/../resources/lang/en.php';

/*
|--------------------------------------------------------------------------
| Library
|--------------------------------------------------------------------------
*/
$database = require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/library/connect.php';
require __DIR__ . '/../src/library/helpers.php';

/*
|--------------------------------------------------------------------------
| Run App
|--------------------------------------------------------------------------
*/

require __DIR__ . '/../src/controllers.php';
require __DIR__ . '/../src/models.php';
require __DIR__ . '/../src/router.php';

/*
|--------------------------------------------------------------------------
| Dispatcher App
|--------------------------------------------------------------------------
*/
try {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = urldecode($uri);

    filter_script($uri);

    router($uri);

} catch (RuntimeException $e) {

    if (getEnv('APP_ENV') == 'dev') {
        $errors = "file: " . $e->getFile() . "\n" . "line: " . $e->getLine() . "\n" . "message: " . $e->getMessage();
        dd($errors);
    }

    header('HTTP1.1 418 Forbidden');
    echo '<html><body><h1>Are you a teapot</h1></body></html>';

}