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

// If user pressed login button, try authenticate user.
CUser::ProcessLogin($db);

// User wants to delete an item.
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Output what will deleted
    // Get content to be deleted. If not exists user is redirected to 404.
    $res = $content->GetItem($id);
    $out .= $content->Delete($id);
} else {
    $out .= "<p>Inget raderades.</p>";
}

$out .= "<p><a href='view.php'>Visa alla</a></p>";
$out .= "<p><a href='create.php'>Skapa ny</a></p>";

// Gather the complete html output for page.
$loom['main'] = $out;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
