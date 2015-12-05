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

// Check first what user wants to do
$action = isset($_GET['action']) ? htmlentities($_GET['action']) : "";
//Create the game or restore it from session
$game = new C100Game();

switch ($action) {
  case 'roll':
    $game->roll();
    break;
  case 'endround':
    $game->endround();
    break;
  case 'restartgame':
    $game->restart();
    break;
  default:
    // No action, just proceed and display page
    break;
}

// Gather the complete html output for page.
$loom['main'] = $game->gameHtml();

// Store game back to session
$_SESSION['c100game'] = serialize($game);

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
