<?php
/**
 * This is a PHP script to process images using PHP GD.
 *
 */
 include '../src/CImage/CImage.php';

// Ensure error reporting is on
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly


// Define some constant values, append slash
// Use DIRECTORY_SEPARATOR to make it work on both windows and unix.
//
define('IMG_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR);
// define('IMG_PATH', 'D:\Users\Fredrik\Documents\GitHub\cimage\webroot' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR);
define('CACHE_PATH', __DIR__ . '/cache/');



/**
 * Display error message.
 *
 * @param string $message the error message to display.
 */
function errorMessage($message) {
  header("Status: 404 Not Found");
  die('img.php says 404 - ' . htmlentities($message));
}



/**
 * Display log message.
 *
 * @param string $message the log message to display.
 */
function verbose($message) {
  echo "<p>" . htmlentities($message) . "</p>";
}

$myImage = new CImage($_GET);

// Get the incoming arguments
//
// Validate incoming arguments
//
// Get information on the image
//
// Calculate new width and height for the image
//
// Creating a filename for the cache
// If there is no valid cached file, create one, store in cache, and output this.
//
// Open up the original image from file
//
// Resize the image if needed
//
// Apply filters and postprocessing of image
//
// Save the image
//
// Output the resulting image
//
$myImage->output();
