<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');
$loom['stylesheets'][] = 'css/loom-cms.css';

// Do it and store it all in variables in the Loom container.

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);

// Get page from CContent
// If $url is not set, or non-existent, user will be redirected to 404
$url = isset($_GET['url']) ? $_GET['url'] : null;

// Gather the complete html output for page.
$loom['main'] = CPage::Get($url, $db);

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
