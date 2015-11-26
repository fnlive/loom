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
    private static $winScore = 50;

    /**
     * Constructor
     *
     */
    function __construct()
    {
      $this->gameRound = new C100Round();
    }

    public function roll()
    {
      $this->lastRoll = $this->gameRound->roll();
    }
    public function endRound()
    {
      // Secure the score in in Round and add them to game score.
      $this->gameScore += $this->gameRound->score();
      $this->gameRound->startRound();
          // $html .= "<p>Du avslutade rundan och säkrade dina poäng. </p>";
    }

    public function restart()
    {
      $this->gameScore = 0;
      $this->lastRoll = 0;
      $this->gameRound = new C100Round();
    }

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

    public function scoreBoard()
    {
      $theArray =   array(
        'lastRoll' => $this->lastRoll,
        'roundScore' => $this->gameRound->score(),
        'gameScore' => $this->gameScore,
      );
      // dump($theArray);
      return $theArray;
    }

    /*
    * Play one turn of the game.
    * One turn can be
    * - roll dice,
    * - end round,
    * - end game.
    * Todo move action switch to caller
    */
    public function play_obs($action)
    {
      $html = "";
        switch ($action) {
          case 'roll':
              $html .= $this->gameRound->playRound();
              break;

          case 'endround':
              $this->gameScore += $this->gameRound->getScore();
              $this->gameRound->startRound();
                  $html .= "<p>Du avslutade rundan och säkrade dina poäng. </p>";
              break;
          case 'endgame':
            // Caller might also want to session_destroy() if object is stored in $_SESSION.
            // However below reset of game should suffice.
            $this->gameScore = 0;
            $this->gameRound = new C100Round();
            break;
          default:
            echo "Did not expect to go here... ";
            break;
        }
        $roundScore = $this->gameRound->getScore();
        $totScore = $this->gameScore + $roundScore;
        if (self::$winScore <= $totScore) {
          $html .= "<p>Du vann med $totScore poäng!</p>";
        } else {
          $html .= "<p>Du har $totScore poäng. $this->gameScore säkra, $roundScore i potten.</p>";
        }
        return <<<EOD

        $html
EOD;
    }
}
