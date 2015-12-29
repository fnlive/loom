<?php
/**
 * This is a PHP skript to process images using PHP GD.
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
$maxWidth = $maxHeight = 2000;


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






$myImage = new CImage();
$myImage->ParseQuery($_GET);
// TODO: Remove below when CImage ready.
//
// Get the incoming arguments
//
$src        = isset($_GET['src'])     ? $_GET['src']      : null;
$verbose    = isset($_GET['verbose']) ? true              : null;
$saveAs     = isset($_GET['save-as']) ? $_GET['save-as']  : null;
$quality    = isset($_GET['quality']) ? $_GET['quality']  : 60;
$ignoreCache = isset($_GET['no-cache']) ? true           : null;
$newWidth   = isset($_GET['width'])   ? $_GET['width']    : null;
$newHeight  = isset($_GET['height'])  ? $_GET['height']   : null;
$cropToFit  = isset($_GET['crop-to-fit']) ? true : null;
$sharpen    = isset($_GET['sharpen']) ? true : null;

$pathToImage = realpath(IMG_PATH . $src);



//
// Validate incoming arguments
//
is_dir(IMG_PATH) or errorMessage('The image dir is not a valid directory.');
is_writable(CACHE_PATH) or errorMessage('The cache dir is not a writable directory.');
isset($src) or errorMessage('Must set src-attribute.');
preg_match('#^[a-z0-9A-Z-_\.\/]+$#', $src) or errorMessage('Filename contains invalid characters.');
substr_compare(IMG_PATH, $pathToImage, 0, strlen(IMG_PATH)) == 0 or errorMessage('Security constraint: Source image is not directly below the directory IMG_PATH.');
is_null($saveAs) or in_array($saveAs, array('png', 'jpg', 'jpeg')) or errorMessage('Not a valid extension to save image as');
is_null($quality) or (is_numeric($quality) and $quality > 0 and $quality <= 100) or errorMessage('Quality out of range');
is_null($newWidth) or (is_numeric($newWidth) and $newWidth > 0 and $newWidth <= $maxWidth) or errorMessage('Width out of range');
is_null($newHeight) or (is_numeric($newHeight) and $newHeight > 0 and $newHeight <= $maxHeight) or errorMessage('Height out of range');
is_null($cropToFit) or ($cropToFit and $newWidth and $newHeight) or errorMessage('Crop to fit needs both width and height to work');




//
// Get information on the image
//
$imgInfo = list($width, $height, $type, $attr) = $myImage->Information($pathToImage);
// echo __FILE__ . " : " . __LINE__ . "<br>";var_dump($imgInfo);


//
// Calculate new width and height for the image
//
$dimensions = list($newWidth, $newHeight) = $myImage->CalcWidthHeight();

//
// Creating a filename for the cache
//
$cacheFileName = $myImage->CacheFileName();



// If there is no valid cached file, create one, store in cache, and output this.
//
// Open up the original image from file
//




//
// Resize the image if needed
//



//
// Apply filters and postprocessing of image
//




//
// Save the image
//






//
// Output the resulting image
//
$myImage->output($cacheFileName);
