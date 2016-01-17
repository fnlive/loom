<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

// Add style for movie_db
$loom['stylesheets'][] = 'css/movie.css';

// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);

// Set up movies to administrate
$movies = new CRMMovieAdmin($db);

// If user pressed login button, try authenticate user.
CUser::ProcessLogin($db);
echo __FILE__ . " : " . __LINE__ . "<br>";dump($_POST);
if (isset($_POST['create'])) {
    // User has pressed create button, so save new movie in database.
    // Save content and then redirect with header... in CCreate:method
    $movies->Create($_POST);
} elseif (isset($_POST['update'])) {
    // User has pressed update button, so update corresponding row in database.
    // Update content and then redirect with header... in CCreate:method
    $movies->Update($_POST);
} elseif (isset($_POST['doFileupload'])) {
    if ('Upload' == $_POST['doFileupload']) {
        $movies->UploadImageFile();
        // Since we tried to upload image file, save name to it in db
        $_POST['image'] = $_FILES["fileToUpload"]["name"];
        // TODO:  We should first see if movie exist in db, if so call ->Update() instead
        // or go back to original form, but insert entered values we had in $_POST
        // or check value on $_POST['update'] to decide if call ->Update instead,
        // but then we need to modify $_POST['update'] dep on if we come from create or update form.
        $movies->Create($_POST);

    }
}


$out = "";
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (isset($_GET['reset-content'])) {
    // Reset database and initialize with default values.
    $out .= $movies->Reset();
    header("Location: rm-movies.php");
} elseif (isset($_GET['delete'])) {
    $out .= $movies->DeleteMovie($id);
} else {
    // User wants to edit an item if id is set. Else create new movie.
    $out .= $movies->getEditContentForm($id);
}

// Do it and store it all in variables in the Loom container.
$loom['title'] = "Movie admin";
$loom['main'] = <<<EOD
<h1>{$loom['title']}</h1>
$out
EOD;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
