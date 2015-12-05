<?php

/**
 * Class to play dice game "100".
 */
class C100Game
{
  /**
   * Properties
   *
   */
    private $gameScore = 0;
    private $gameRound;
    private $lastRoll = 0;
    private $htmlMsg = "";
    private static $winScore = 20;

    /**
     * Constructor
     *
     */
    function __construct()
    {
        $this->lastRoll = 0;
        $this->htmlMsg = "";
        if(isset($_SESSION['c100game'])) {
            //Restore game from session
          $tempGame = unserialize($_SESSION['c100game']);
          $this->gameScore = $tempGame->gameScore;
          $this->gameRound = $tempGame->gameRound;
      } else {
          $this->gameScore = 0;
          $this->gameRound = new C100Round();
      }
    }

    /**
     * Play one turn by rolling the dice
     *
     */
    public function roll()
    {
      $this->lastRoll = $this->gameRound->roll();
    }

    /**
     * End round and secure score from round
     *
     */
    public function endRound()
    {
      // Secure the score in Round and add them to game score.
      $this->gameScore += $this->gameRound->score();
      $this->gameRound->startRound();
      $this->htmlMsg = "<p>Du avslutade rundan och säkrade dina poäng. </p>";
    }

    /**
     * Restart the game and reset all scores.
     *
     */
    public function restart()
    {
      $this->gameScore = 0;
      $this->lastRoll = 0;
      $this->gameRound = new C100Round();
    }

    /**
     * Check if player has won the game.
     * Return true if win, atherwise false.
     *
     */
    public function win()
    {
      $totScore = $this->gameScore + $this->gameRound->score();
      $win = false;
      if (self::$winScore <= $totScore) {
        $win = true;
      } else {
        $win = false;
      }
      return $win;
    }

    /**
     * Return game score board
     *
     */
    public function scoreBoard()
    {
      $theArray =   array(
        'lastRoll' => $this->lastRoll,
        'roundScore' => $this->gameRound->score(),
        'gameScore' => $this->gameScore,
        'gameWon' => $game->win(),
      );
      return $theArray;
    }

    /**
     * Return html to display game control buttons
     *
     */
    public function controlsHtml()
    {
        $htmlControls = <<<EOD
        <div class="game-control">
          <a class="game-button" href="?action=roll">Slå tärning</a>
          <a class="game-button" href="?action=endround">Säkra potten</a>
          <a class="game-button" href="?action=restartgame">Starta om spelet</a>
        </div>
EOD;
        return $htmlControls;
    }

    /**
     * Return html to display game score board
     *
     */
    public function scoreBoardHtml()
    {
        // $htmlMsg = "";
        //Check if player won the game.
        if ($this->win()) {
          $this->htmlMsg .= "Du vann spelet!";
        }
        // $scoreboard = $this->scoreBoard();
        $roundScore = $this->gameRound->score();
        // Gather html output for the scoreboard
        $htmlScore = <<<EOD
        <div class="scoreboard">
          <div class="score">
              <div class="score-cap">Slag</div>
              <div class="score-point">$this->lastRoll</div>
          </div>
          <div class="score">
            <div class="score-cap">Potten</div>
            <div class="score-point">$roundScore</div>
          </div>
          <div class="score">
              <div class="score-cap">Säkrade</div>
              <div class="score-point">$this->gameScore</div>
          </div>
          <div class="game-message">$this->htmlMsg</div>
        </div>
EOD;
        $this->htmlMsg = "";    // Reset Message since it has been outputted.
        return $htmlScore;
    }

    /**
     * Return complete html for game to display on page
     *
     */
    public function gameHtml()
    {
        // Gather the complete html output for game.
        $htmlControls = $this->controlsHtml();
        $htmlScore = $this->scoreBoardHtml();
        return <<<EOD
        <h1 id="hundred">Tärningsspelet 100</h1>
        <p>Samla ihop poäng för att komma först till 100. I varje omgång kastar du  tärning tills du väljer att stanna och säkra potten eller tills det dyker upp en 1:a och du förlorar alla poäng som samlats in i rundan. Slå tärningen för att starta spelet.</p>
        $htmlControls
        $htmlScore
EOD;
    }

}
