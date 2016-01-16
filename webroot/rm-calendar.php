<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

// Add style for movie_db
$loom['stylesheets'][] = 'css/rm-calendar.css';
$loom['stylesheets'][] = 'css/rm-movie.css';

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);




$loom['title'] = "Månadens film";
$out = "";

// Get html-output for calender
// If month is null show todays month
$date = isset($_GET['date']) ? $_GET['date'] : null;
$out .= CRMCalendar::output($db, $date);

// Do it and store it all in variables in the Loom container.
$loom['main'] = <<<EOD
<h1>{$loom['title']}</h1>
$out
EOD;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
