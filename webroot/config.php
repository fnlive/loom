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
 * Create user. After this we can check if user is authenticated or not.
 *
 */
$user = new CUser();

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
$loom['title_append'] = ' | Rental Movies';


/**
 * Theme related settings.
 *
 */
$loom['stylesheets'] = array('css/style.css');
$loom['favicon']    = 'favicon.ico';
/**
 * Define the menu as an array
 */
$loom['mainnavbar']  = array(
  // Use for styling the menu
  'class' => 'navbar',

    // Here comes the menu
    'items' => array(
        'first-page'  => array(
        'text'  =>'Hem',
        'url'   =>'rm-home.php',
        'title' => 'Hem'
        ),
        'movies' => array('text'=>'Filmer', 'url'=>'rm-movies.php', 'title' => 'Filmer'),
        'news' => array('text'=>'Nyheter', 'url'=>'post.php', 'title' => 'Nyheter'),
        'about' => array('text'=>'Om oss', 'url'=>'page.php?url=om-oss', 'title' => 'Om oss'),
        'game-1' => array('text'=>'Tävla', 'url'=>'dice-100.php', 'title' => 'Tävla'),
        'user' => array(), // Reserve position for user menu
        'source' => array('text'=>'Källkod', 'url'=>'source.php', 'title' => 'Källkod'),
      ),
      // This is the callback tracing the current selected menu item base on scriptname
      'callback' => function($url) {
        if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
          return true;
        }
      }
);

// Add "admin-items" to menu if user is authenticated with correct privilege
if (CUser::IsAuthenticated()) {
    $loom['mainnavbar']['items']['news']['submenu'] = array(
          'items' => array(
            // This is a menu item of the submenu
            'create'  => array(
              'text'  => 'Skapa Nyhet',
              'url'   => 'create.php',
              'title' => 'Skapa nyhet',
            ),
            // This is a menu item of the submenu
            'view'  => array(
              'text'  => 'Administrera och visa Nyheter',
              'url'   => 'view.php',
              'title' => 'Administrera och visa Nyheter',
            ),
            // This is a menu item of the submenu
            'reset'  => array(
              'text'  => 'Återställ innehåll',
              'url'   => 'view.php?reset-content',
              'title' => 'Återställ innehåll',
            ),
          ),
    );

    $loom['mainnavbar']['items']['movies']['submenu'] = array(
      'items' => array(
        // This is a menu item of the submenu
        'create'  => array(
          'text'  => 'Skapa ny Film',
          'url'   => 'rm-movieadmin.php',
          'title' => 'Skapa ny Film',
        ),
        // This is a menu item of the submenu
        'adm-view'  => array(
          'text'  => 'Administrera och visa Filmer',
          'url'   => 'rm-movieadminview.php',
          'title' => 'Administrera och visa Filmer',
        ),
        // This is a menu item of the submenu
        'reset'  => array(
          'text'  => 'Återställ innehåll',
          'url'   => 'rm-movieadmin.php?reset-content',
          'title' => 'Återställ innehåll',
        ),
      ),
  );

    $loom['mainnavbar']['items']['user'] = array(
        'text'  =>'Logout',
        'url'   =>'logout.php',
        'title' => 'Logout',
        'submenu' => array(
          'items' => array(
            'status'  => array(
              'text'  => 'Status',
              'url'   => 'status.php',
              'title' => 'Logout',
            ),
          ),
        ),
  ); //end user menu
} else {
    // Add menu items for users not authenticated
    $loom['mainnavbar']['items']['user'] = array(
        'text'  =>'Login',
        'url'   =>'login.php',
        'title' => 'Login',
        'submenu' => array(
          'items' => array(
            'status'  => array(
              'text'  => 'Status',
              'url'   => 'status.php',
              'title' => 'Logout',
            ),
          ),
        ),
  ); //end user menu
}

/**
* Settings for the database.
*
*/
if('localhost' == $_SERVER['SERVER_NAME']) {
// if (0) {
    // echo 'We are localhost...';
    define('DB_PASSWORD', '');
    // $loom['database']['dsn']            = 'mysql:host=localhost;dbname=MovieDb;'; //To get kmom04 working
    $loom['database']['dsn']            = 'mysql:host=localhost;dbname=LoomCms;'; // To get later kmom working
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
