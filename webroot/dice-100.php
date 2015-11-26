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

$html = "";
$htmlWin = "";
switch ($action) {
  case 'roll':
    $game->roll();
    break;
  case 'endround':
    $game->endround();
    $html .= "<p>Du avslutade rundan och säkrade dina poäng. </p>";
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
  $htmlWin = "Du vann spelet!";
}
// dump($scoreboard);
$htmlScore = <<<EOD
<div class="scoreboard">
  <div class="score">
    <p>
      Senaste slag: {$scoreboard['lastRoll']}
    </p>
  </div>
  <div class="score">
    <p>
      Säkra poäng: {$scoreboard['gameScore']}
    </p>
  </div>
  <div class="score">
    Poäng i rundan:  {$scoreboard['roundScore']}
  </div>
</div>
EOD;
 ?>

<?php
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
$htmlWin
$html
$htmlScore
<p><a href="?action=roll">Slå tärning</a></p>
<p><a href="?action=endround">Avsluta rundan</a></p>
<p><a href="?action=restartgame">Starta om spelet</a></p>
EOD;

?>
<p></p>
<?php

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
