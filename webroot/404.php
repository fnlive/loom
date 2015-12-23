<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');


// Do it and store it all in variables in the loom container.
$loom['title'] = "404";
$loom['header'] = "";
$loom['main'] = "This is a loom 404. Document is not here.";
$loom['footer'] = "";

// Send the 404 header
header("HTTP/1.0 404 Not Found");


// Finally, leave it all to the rendering phase of loom.
include(LOOM_THEME_PATH);
