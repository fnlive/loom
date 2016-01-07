<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');


// Do it and store it all in variables in the loom container.
$title = "Film 404";
$loom['title'] = $title;
$loom['header'] = $title;
$loom['main'] = "<h1>$title</h1><p>Filmen finns inte. Försök  <a href=\"rm-movies.php\">sök</a> efter en annan film.</p>";
$loom['footer'] = "";


// Send the 404 header
header("HTTP/1.0 404 Not Found");


// Finally, leave it all to the rendering phase of loom.
include(LOOM_THEME_PATH);
