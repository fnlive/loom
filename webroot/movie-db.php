<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

// Add style for movie_db
$loom['stylesheets'][] = 'css/movie.css';

$loom['title'] = "movieDb";

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);

$movieSearch = new CMovieSearch($db, $_GET);
// Get html-output for movie search form and
// movie search results.
$out = $movieSearch->output();

// Do it and store it all in variables in the Anax container.
$loom['title'] = "Visa filmer med sökalternativ kombinerade";
$loom['main'] = <<<EOD
<h1>{$loom['title']}</h1>
$out
EOD;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
