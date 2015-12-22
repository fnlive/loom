<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

$loom['title'] = "Radera innehåll";
$out = "";

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);
$content = new CContent($db);

// User wants to delete an item.
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $content->Delete($id);
    // Output what was deleted
    $res = $content->GetItem($id);
    $url = $content->getUrl($res[0]);
    $out .= "<p>Du har raderat <a href='$url'>{$res[0]->title}</a>.</p>";
} else {
    $out .= "<p>Inget raderades.</p>";
}

$out .= "<p><a href='view.php'>Visa alla</a></p>";
$out .= "<p><a href='create.php'>Skapa ny</a></p>";

// Gather the complete html output for page.
$loom['main'] = $out;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
