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
    'report'  => array('text'=>'Redovisning',  'url'=>'report.php'),
    'source' => array('text'=>'Källkod', 'url'=>'source.php'),
);
