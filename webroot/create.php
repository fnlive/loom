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

// User has pressed save button in create-form, save item to database.
if (isset($_POST['save'])) {
    //Save content and then redirect with header... in CCreate:method todo
    $title = isset($_POST['title']) ? strip_tags($_POST['title']) : "NULL";
    $slug = isset($_POST['slug']) ? strip_tags($_POST['slug']) : "NULL";
    $slug = empty($slug) ? null : $slug;
    $url = isset($_POST['url']) ? strip_tags($_POST['url']) : "NULL";
    $url = empty($url) ? null : $url;
    $data = isset($_POST['data']) ? strip_tags($_POST['data']) : "NULL";
    $type = isset($_POST['type']) ? strip_tags($_POST['type']) : "NULL";
    $filter = isset($_POST['filter']) ? strip_tags($_POST['filter']) : "NULL";
    $published = isset($_POST['published']) ? strip_tags($_POST['published']) : "NULL";
    $post2Save = array(
        $slug,
        $url,
        $type,
        $title,
        $data,
        $filter,
        $published,
    );
    $content->Save($post2Save);
}

$out .= $content->getCreateContentForm();
$out .= "<p><a href='view.php'>Visa alla</a></p>";

// Gather the complete html output for page.
$loom['main'] = $out;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
