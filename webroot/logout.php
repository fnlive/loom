<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

$loom['title'] = "Logout";
$output = "";

$user = new CUser();
$output .= $user->LogoutAndOutputHTML();
$output .= '<p><a href="login.php">Logga in</a></p>';
$output .= '<p><a href="status.php">Status</a></p>';

// Gather the complete html output for page.
$loom['main'] = "" . $output;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
