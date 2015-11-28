<?php

/**
 * Class to do one round in game "100"
 */
class C100Round
{
  /**
   * Properties
   *
   */
    // Keep score of current round
    private $score = 0;

    /**
     * Start round. Reset score in round.
     *
     */
    public function startRound()
    {
        $this->score = 0;
    }

    /**
     * Play one turn by rolling the dice.
     * If you roll a "1" you lose your current round score.
     *
     */
    public function roll()
    {
      $roll = CDice::Roll();
      if (1==$roll) {
          $this->score = 0;
      } else {
          $this->score += $roll;
      }
      return $roll;
    }

  /**
   * Get current score in round.
   *
   */
    public function score()
    {
        return $this->score;
    }

}
