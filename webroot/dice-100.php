<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

// Add style for dice-100
$loom['stylesheets'][] = 'css/dice-100.css';

$loom['title'] = "100";

// Play to 100 game
$game = new C100Game();

// Gather the complete html output for page.
$loom['main'] = $game->play();


// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
