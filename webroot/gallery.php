<?php
/**
 * This is a loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');
$loom['stylesheets'][] = 'css/figure.css';
$loom['stylesheets'][] = 'css/gallery.css';
$loom['stylesheets'][] = 'css/breadcrumb.css';



/**
 * Display error message.
 *
 * @param string $message the error message to display.
 */
function errorMessage($message) {
  header("Status: 404 Not Found");
  die('gallery.php says 404 - ' . htmlentities($message));
}


//
// Define the basedir for the gallery
//
define('GALLERY_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'img');
define('GALLERY_BASEURL', '');


//
// Get incoming parameters
//
$path = isset($_GET['path']) ? $_GET['path'] : null;

//
// Create gallery object and generate html for gallery.
//
$myGallery = new CGallery(GALLERY_PATH, GALLERY_BASEURL, $path);


//
// Prepare content and store it all in variables in the loom container.
//
$gallery = $myGallery->Output();
$breadcrumb = $myGallery->CreateBreadcrumb();

$loom['title'] = "Ett galleri";
$loom['main'] = <<<EOD
<h1>{$loom['title']}</h1>

$breadcrumb

$gallery

EOD;



// Finally, leave it all to the rendering phase of loom.
include(LOOM_THEME_PATH);
