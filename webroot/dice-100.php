<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');


// Do it and store it all in variables in the Loom container.
$loom['title'] = "100";

// Play to 100 game
// Check first what user wants to do
$action = isset($_GET['action']) ? htmlentities($_GET['action']) : "";
//Create the game or restore it from session
if(isset($_SESSION['c100game'])) {
    $game = unserialize($_SESSION['c100game']);
} else {
    $game = new C100Game();
}
$html = $game->play($action);
// Store game back to session
$_SESSION['c100game'] = serialize($game);
if ('endgame'==$action) {
    if (session_destroy()) {
        // echo "Session destroyed. ";
    } else {
        echo "Could not destroy session. ";
    }
}

// <p>Action: $action</p>
$loom['main'] = <<<EOD
<h1>Tärningsspelet 100</h1>
<p>Samla ihop poäng för att komma först till 100. I varje omgång kastar du  tärning tills du väljer att stanna och spara poängen eller tills det dyker upp en 1:a och du förlorar alla poäng som samlats in i rundan. Slå tärningen för att starta spelet.</p>
$html
EOD;

?>
<p></p>
<?php

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
