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
    private $gameSum = 0;
    private $gameRound;
    private static $winScore = 50;

    /**
     * Constructor
     *
     */
    function __construct()
    {
      $this->gameRound = new C100Round();
    }

    /**
    * Play one turn of the game.
    * One turn can be
    * - roll dice,
    * - end round,
    * - end game.
    * Todo move action switch to caller
    */
    public function play($action)
    {
      $html = "";
        switch ($action) {
          case 'roll':
              $html .= $this->gameRound->playRound();
              break;

          case 'endround':
              $this->gameSum += $this->gameRound->getScore();
              $this->gameRound->startRound();
                  $html .= "<p>Du avslutade rundan och säkrade dina poäng. </p>";
              break;
          case 'endgame':
            // Caller might also want to session_destroy() if object is stored in $_SESSION.
            // However below reset of game should suffice.
            $this->gameSum = 0;
            $this->gameRound = new C100Round();
            break;
          default:
            echo "Did not expect to go here... ";
            break;
        }
        $roundScore = $this->gameRound->getScore();
        $totScore = $this->gameSum + $roundScore;
        if (self::$winScore <= $totScore) {
          $html .= "<p>Du vann med $totScore poäng!</p>";
        } else {
          $html .= "<p>Du har $totScore poäng. $this->gameSum säkra, $roundScore i potten.</p>";
        }
        return <<<EOD
        <p><a href="?action=roll">Slå tärning</a></p>
        <p><a href="?action=endround">Avsluta rundan</a></p>
        <p><a href="?action=endgame">Starta om spelet</a></p>
        $html
EOD;
    }
}
