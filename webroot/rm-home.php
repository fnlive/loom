<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

// Add style for movie_db
$loom['stylesheets'][] = 'css/rm-movie.css';
$loom['stylesheets'][] = 'css/loom-cms.css';

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);

$loom['title'] = "Hem";
$out = "";

// Visa de tre nyaste filmerna (senast uppdaterade filmerna).
$out .= "<h2>Senaste filmer</h2>";
$searchParams = array('orderby' => 'updated', 'order' => 'desc', 'hits' => 3);
$movieSearch = new CRMMovieSearch($db, $searchParams);
$out .= $movieSearch->outputSearchResults();
$out .= "<div class=\"clear-both\"></div>";

// Visa de tre senaste blogginläggen.
$out .= "<h2>Nyheter</h2>";
$out .= CBlog::GetLatest($db, 3);

// Visa en översikt av de kategorier som finns för filmerna.
$out .= "<h2>Filmgenrer</h2>";
$movieSearch = new CRMMovieSearch($db);
$out .= $movieSearch->outputGenreLinks();

// Visa bilder på mest populära film och senast hyrda film (okey att hårdkoda).
// TODO: dela i två...
$out .= "<h2>Mest populära och hyrda filmer</h2>";
$movies = new CRMMovie($db, 8);
$out .= $movies->outputMovieCard();
$movies = new CRMMovie($db, 3);
$out .= $movies->outputMovieCard();
$movies = new CRMMovie($db, 5);
$out .= $movies->outputMovieCard();
// Lägg till övrig information efter eget tycke för att göra en presentabel första sida.

// Do it and store it all in variables in the Loom container.
$loom['main'] = <<<EOD
<h1>{$loom['title']}</h1>
$out
EOD;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
