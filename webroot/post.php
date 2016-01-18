<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);

$loom['stylesheets'][] = 'css/loom-cms.css';

$out = "";
// Get posts from CContent
// If $slug is not set show all posts
// IF $slug is non-existent, user will be shown a empty blog page.
$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$category = isset($_GET['category']) ? $_GET['category'] : null;
if ($slug) {
    $out .= CBlog::Get($slug, $db);
} elseif ($category) {
    $out .= CBlog::GetByCategory($category, $db);
} else {
    $out .= CBlog::Get('', $db);
}
$loom['title'] = "Blogg";

// Gather the complete html output for page.
$loom['main'] = $out;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
