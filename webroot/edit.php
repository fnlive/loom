<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

$loom['title'] = "Redigera innehåll";
$out = "";

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);
$content = new CContent($db);

// User wants to edit an item.
// Get html for for update of item and pre-fill it with values from database.
if (isset($_GET['id'])) {
    $out .= $content->getEditContentForm($_GET['id']);
}
// User has pressed update button, so update corresponding row in database.
if (isset($_POST['update'])) {
    //Save content and then redirect with header... in CCreate:method
    $content->Update($_POST);
}

$out .= "<p><a href='view.php'>Visa alla</a></p>";
$out .= "<p><a href='create.php'>Skapa ny</a></p>";

// Gather the complete html output for page.
$loom['main'] = $out;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
