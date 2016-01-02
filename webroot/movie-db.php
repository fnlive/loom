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

// Get search result from movie database
$res = $movieSearch->Search();

// Prepare to put results into rows for a HTML-table.
$searchResultTable = new CHTMLTable($res);

list($max, $rows) = $movieSearch->MaxPages();

// Do it and store it all in variables in the Anax container.
$loom['title'] = "Visa filmer med sökalternativ kombinerade";

$hitsPerPage = CMovieNav::getHitsPerPage(array(2, 4, 8), $hits);
$navigatePage = CMovieNav::getPageNavigation($hits, $page, $max);

$loom['main'] = <<<EOD
<h1>{$loom['title']}</h1>
{$movieSearch->outputForm()}
{$searchResultTable->output($rows, $hitsPerPage, $navigatePage)}
EOD;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
