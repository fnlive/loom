<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

$loom['title'] = "Skapa innehåll";
$out = "";

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);
$content = new CContent($db);

// If user pressed login button, try authenticate user.
CUser::ProcessLogin($db);

// User has pressed save button in create-form, save item to database.
if (isset($_POST['save'])) {
    //Save content and then redirect to edit.php
    $content->Save($_POST);
    // Check status of save to db Todo
}

$out .= $content->getCreateContentForm();
$out .= "<p><a href='view.php'>Visa alla</a></p>";

// Gather the complete html output for page.
$loom['main'] = $out;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
