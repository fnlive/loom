<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

$loom['title'] = "Status";
$output = "";

$user = new CUser();

if (CUser::IsAuthenticated()) {
    $acro = CUser::GetAcronym();
    $name = CUser::GetName();
    $output .= "<p>Du är inloggad som: {$name} ({$acro}).</p>";
    $output .= '<p><a href="logout.php">Logga ut</a></p>';
}
else {
    $output = "<p>Du är inte inloggad.</p>";
    $output .= '<p><a href="login.php">Logga in</a></p>';
}

// Gather the complete html output for page.
$loom['main'] = "" . $output;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
