<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

$loom['title'] = "Login";
$output = "";

// Connect to a movie database
$db = new CDatabase($loom['database']);
$user = new CUser();

// If user pressed login button, try authenticate user.
if (isset($_POST['submit'])) {
  $user->Login($_POST['user'], $_POST['passwd'], $db);
}

// Get login form
$output .= $user->LoginForm();
if ($user->IsAuthenticated()) {
  $output .= '<p><a href="logout.php">Logga ut</a></p>';
}
$output .= '<p><a href="status.php">Status</a></p>';

// Gather the complete html output for page.
$loom['main'] = "" . $output;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
