<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Add style for csource
$loom['stylesheets'][] = 'css/dice-100.css';

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

$htmlMsg = "";
switch ($action) {
  case 'roll':
    $game->roll();
    break;
  case 'endround':
    $game->endround();
    $htmlMsg .= "<p>Du avslutade rundan och säkrade dina poäng. </p>";
    break;
  case 'restartgame':
    $game->restart();
    break;
  default:
    echo "Did not expect to go here... ";
    break;
}

$scoreboard = $game->scoreBoard();
//Check om spelaren har kommmit över vinstgräns.
if ($game->win()) {
  $htmlMsg = "Du vann spelet!";
}
// dump($scoreboard);
$htmlScore = <<<EOD
<div class="scoreboard">
  <div class="score">
      <div class="score-cap">Slag</div>
      <div class="score-point">{$scoreboard['lastRoll']}</div>
  </div>
  <div class="score">
    <div class="score-cap">Potten</div>
    <div class="score-point">{$scoreboard['roundScore']}</div>
  </div>
  <div class="score">
      <div class="score-cap">Säkrade</div>
      <div class="score-point">{$scoreboard['gameScore']}</div>
  </div>
  <div class="game-message">$htmlMsg</div>
</div>
EOD;
// <div class="clearfix"></div>

$htmlControls = <<<EOD
<div class="game-control">
<a class="game-button" href="?action=roll">Slå tärning</a>
<a class="game-button" href="?action=endround">Säkra potten</a>
<a class="game-button" href="?action=restartgame">Starta om spelet</a>
</div>
EOD;

// Store game back to session
$_SESSION['c100game'] = serialize($game);

//Check if user wants to end game. If so, destroy session.
// Game is also resetted by $game->restart(); so this is not really needed.
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
$htmlControls
$htmlScore

EOD;

?>
<p></p>
<?php

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
