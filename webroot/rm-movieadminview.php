<?php
/**
 * This is a Loom pagecontroller.
 * Show search form and overview of movies
 * together with administration links like edit, delete, ...
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

if (isset($_POST['title'])) {
    // User is searchin movies through search form
    $movieSearch = new CRMMovieSearch($db, $_POST);
    $out = $movieSearch->output();
} else {
    // User is searchin movies via url query
    $movieSearch = new CRMMovieSearch($db, $_GET);
    // Get html-output for movie search form and
    // movie search results.
    $out = $movieSearch->output();
}

// Do it and store it all in variables in the Loom container.
$loom['title'] = "Filmer - Administration";
$loom['main'] = <<<EOD
<h1>{$loom['title']}</h1>
$out
EOD;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
