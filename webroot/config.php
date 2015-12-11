<?php
/**
 * Config-file for Loom. Change settings here to affect installation.
 *
 */

/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly


/**
 * Define Loom paths.
 *
 */
define('LOOM_INSTALL_PATH', __DIR__ . '/..');
define('LOOM_THEME_PATH', LOOM_INSTALL_PATH . '/theme/render.php');


/**
 * Include bootstrapping functions.
 *
 */
include(LOOM_INSTALL_PATH . '/src/bootstrap.php');


/**
 * Start the session.
 *
 */
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();


/**
 * Create the Loom variable.
 *
 */
$loom = array();


/**
 * Site wide settings.
 *
 */
$loom['lang']         = 'sv';
$loom['title_append'] = ' | Loom';


/**
 * Theme related settings.
 *
 */
$loom['stylesheets'] = array('css/style.css');
$loom['favicon']    = 'favicon.ico';
// echo CNavBar::GenerateMenu();
$loom['mainnavbar'] = array(
    'me' => array('text'=>'Me', 'url'=>'me.php'),
    '100' => array('text'=>'100', 'url'=>'dice-100.php'),
    'movieDb' => array('text'=>'movieDb', 'url'=>'movie-db.php'),
    'report'  => array('text'=>'Redovisning',  'url'=>'report.php'),
    'source' => array('text'=>'Källkod', 'url'=>'source.php'),
    'login' => array('text'=>'Login', 'url'=>'login.php'),
);

/**
* Settings for the database.
*
*/
if('localhost' == $_SERVER['SERVER_NAME']) {
// if (0) {
    // echo 'We are localhost...';
    define('DB_PASSWORD', '');
    $loom['database']['dsn']            = 'mysql:host=localhost;dbname=MovieDb;';
    $loom['database']['username']       = 'root';
} else {
    // echo 'We are on a remote location far, far away...';
    define('DB_PASSWORD', 'D39EIl6,');
    $loom['database']['dsn']            = 'mysql:host=blu-ray.student.bth.se;dbname=frnf15;';
    $loom['database']['username']       = 'frnf15';
}
$loom['database']['password']       = DB_PASSWORD;
$loom['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
// dump($loom['database']);
