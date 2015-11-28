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
    private static $winScore = 100;

    /**
     * Constructor
     *
     */
    function __construct()
    {
      $this->gameRound = new C100Round();
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
      // Secure the score in in Round and add them to game score.
      $this->gameScore += $this->gameRound->score();
      $this->gameRound->startRound();
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
      );
      return $theArray;
    }
    
}
