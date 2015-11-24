<?php

/**
 * Class to do one round in game "100"
 */
class C100Round
{
    // Keep score of current round
    private $score = 0;

    /**
     * Constructor
     *
     */
    function __construct()
    {
        # code...
    }
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
    public function playRound()
    {
        $html = "";
        $roll = CDice::Roll();
        $html .= "<p>Du slog en $roll'a. </p><p></p>";
        if (1==$roll) {
            $this->score = 0;
        } else {
            $this->score += $roll;
        }
        return $html;
    }

    /**
     * Get current score in round.
     *
     */
    public function getScore()
    {
        return $this->score;
    }

}
