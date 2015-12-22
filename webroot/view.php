<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');
$loom['stylesheets'][] = 'css/loom-cms.css';

// Do it and store it all in variables in the Loom container.

$loom['title'] = "Visa innehåll";
$out = "";

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);
$content = new CContent($db);

// Get all posts and pages from CContent
// or test if user wants to see specific post with _GET
if (isset($_GET['show'])) {
    $out .= $content->ShowItems($_GET['show']);
} else {
    $out .= $content->ShowItems();
}
$out .= "<p><a href='create.php'>Skapa ny</a></p>";
$out .= "<p>
<a href='view.php?show=published'>Publicerade</a> |
<a href='view.php?show=draft'>Utkast</a> |
<a href='view.php?show=deleted'>Raderade</a> |
<a href='view.php?show=all'>Visa alla</a>
    </p>";

// Gather the complete html output for page.
$loom['main'] = $out;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
