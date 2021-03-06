<?php
/**
 * Display movies and genres from db. Paginate output.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

// Add style for movie_db
$loom['stylesheets'][] = 'css/rm-movie.css';
$loom['stylesheets'][] = 'css/loom-cms.css';

$loom['title'] = "movieDb";

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);

if (isset($_POST['title'])) {
    // User is searchin movies through search form
    $movieSearch = new CRMMovieSearch($db, $_POST);
    $out = $movieSearch->output();
} elseif (isset($_POST['title-simple'])) {
    // User is searchin movies through simple search form
    $_POST['title'] = $_POST['title-simple'];
    $movieSearch = new CRMMovieSearch($db, $_POST);
    $out = $movieSearch->outputUserView();
}else {
    // User is searchin movies via url query
    $movieSearch = new CRMMovieSearch($db, $_GET);
    // Get html-output for movie search form and
    // movie search results.
    $out = $movieSearch->outputUserView();
}

// Do it and store it all in variables in the Loom container.
$loom['title'] = "Filmer";
$loom['main'] = <<<EOD
$out
EOD;
// <h1>{$loom['title']}</h1>

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
