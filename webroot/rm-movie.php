<?php
/**
 * Display single movie.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

// Add style for movie_db
$loom['stylesheets'][] = 'css/rm-movie.css';

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);

// Get html-output for single movie
$id = isset($_GET['id']) ? $_GET['id'] : null;
$movie = new CRMMovie($db, $id);
$loom['title'] = $movie->title();
$out = $movie->output();


// Do it and store it all in variables in the Loom container.
$loom['main'] = $out;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
