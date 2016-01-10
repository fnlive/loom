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

  // Here comes the menu strcture
  'items' => array(
      'first-page'  => array(
        'text'  =>'Hem',
        'url'   =>'rm-home.php',
        'title' => 'Hem'
      ),
    // This is a menu item
    'home'  => array(
      'text'  =>'Me',
      'url'   =>'me.php',
      'title' => 'Me'
    ),
        '100' => array('text'=>'100', 'url'=>'dice-100.php',
        'title' => '100'),
        'movieDb' => array('text'=>'movieDb', 'url'=>'movie-db.php',
        'title' => 'movieDb'),
        'report'  => array('text'=>'Redovisning',  'url'=>'report.php',
        'title' => 'Redovisning'),
        'source' => array('text'=>'Källkod', 'url'=>'source.php',
        'title' => 'Källkod'),
    // This is a menu item
    'user'  => array(
      'text'  =>'User',
      'url'   =>'login.php',
      'title' => 'Login',

      // Here we add the submenu, with some menu items, as part of a existing menu item
      'submenu' => array(

        'items' => array(
          // This is a menu item of the submenu
          'login'  => array(
            'text'  => 'Login',
            'url'   => 'login.php',
            'title' => 'Login',
          ),
          // This is a menu item of the submenu
          'logout'  => array(
            'text'  => 'Logout',
            'url'   => 'logout.php',
            'title' => 'Logout',
            'class' => 'italic'
          ),
          'status'  => array(
            'text'  => 'Status',
            'url'   => 'status.php',
            'title' => 'Logout',
          ),
        ),
      ),
    ),
    'cmsAdmin' => array('text'=>'Admin', 'url'=>'view.php', 'title' => 'CmsAdmin',
    // Here we add the submenu, with some menu items, as part of a existing menu item
    'submenu' => array(

      'items' => array(
        // This is a menu item of the submenu
        'create'  => array(
          'text'  => 'Skapa ny',
          'url'   => 'create.php',
          'title' => 'Skapa ny',
        ),
        // This is a menu item of the submenu
        'view'  => array(
          'text'  => 'Visa alla',
          'url'   => 'view.php',
          'title' => 'Visa alla',
        ),
        // This is a menu item of the submenu
        'reset'  => array(
          'text'  => 'Återställ innehåll',
          'url'   => 'view.php?reset-content',
          'title' => 'Återställ innehåll',
        ),
      ),
    ),
  ),
  'blogg' => array('text'=>'Blogg', 'url'=>'post.php', 'title' => 'Bloggen'),
  'galleri' => array('text'=>'Galleri', 'url'=>'gallery.php', 'title' => 'Galleri'),
  'Movies' => array('text'=>'Filmer', 'url'=>'rm-movies.php', 'title' => 'Filmer',
  // Here we add the submenu, with some menu items, as part of a existing menu item
  'submenu' => array(

    'items' => array(
      // This is a menu item of the submenu
      'create'  => array(
        'text'  => 'Skapa ny',
        'url'   => 'rm-movieadmin.php',
        'title' => 'Skapa ny',
      ),
      // This is a menu item of the submenu
      'view'  => array(
        'text'  => 'Visa alla',
        'url'   => 'rm-movies.php',
        'title' => 'Visa alla',
      ),
      // This is a menu item of the submenu
      'reset'  => array(
        'text'  => 'Återställ innehåll',
        'url'   => 'rm-movieadmin.php?reset-content',
        'title' => 'Återställ innehåll',
      ),
    ),
  ),
),
  ),

  // This is the callback tracing the current selected menu item base on scriptname
  'callback' => function($url) {
    if(basename($_SERVER['SCRIPT_FILENAME']) == $url) {
      return true;
    }
  }
);


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
