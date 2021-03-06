<?php
/**
 *
 */
class CRM100Game extends C100Game
{
    private $database = null;

    public function SetMovieDb($database)
    {
        $this->database = $database;
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
        $out = "<h1 id='hundred'>Kasta tärning och vinn en film</h1>";
        $out .= "<div class='game-board'>";
        $out .= parent::$instructions;
        if ($this->win()) {
            $out .= "<h2>Grattis, du vann en film</h2>";
            $db = new CDatabase($this->database);
            // TODO: randomise movie id. First get number of movies from db.
            $movies = new CRMMovie($db, 3);
            $out .= $movies->outputMovieCard();
        } else {
            $out .= $htmlControls . $htmlScore;
        }
        $out .= "</div>";
        return $out;
    }
}
