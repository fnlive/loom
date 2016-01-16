<?php

/**
 * Class for presenting a single movie
 */
class CRMMovie
{
    private $res = null;

    function __construct($db, $id)
    {
        // Get movie info together with genres for movie.
        $query = "SELECT * FROM rm_movies WHERE id = ?;";
        $query = '
          SELECT
            M.*,
            GROUP_CONCAT(G.name) AS genre
          FROM rm_movies AS M
            LEFT OUTER JOIN rm_movie2genre AS M2G
              ON M.id = M2G.idMovie
            INNER JOIN rm_genre AS G
              ON M2G.idGenre = G.id
            WHERE M.id = ?
        ';
        $res = $db->ExecuteSelectQueryAndFetchAll($query, array($id), false);
        // echo __FILE__ . " : " . __LINE__ . "<br>";dump($res);
        $this->res = isset($res[0]) ? $res[0] : null;
    }

    public function Exists()
    {
        return (null == $this->res) ? false : true;
    }

    public function title()
    {
        return $this->Exists() ? htmlentities($this->res->title) : "Film 404";
    }

    public static function GenreLinks($genreList)
    {
        $genres = explode(",", $genreList);
        $firstBar = false;
        $out = "";
        foreach ($genres as $genre) {
            $out .= $firstBar ? " | " : "";
            $firstBar = true;
            $out .= "<a href=\"rm-movies.php?genre=$genre\">$genre</a>";
        }
        return $out;
    }

    public function outputMovieCard()
    {
        return CRMMovieView::output(array($this->res));
    }

    public function output()
    {
        if (!$this->Exists()) {
            header("Location: rm-movie-404.php");
        }
        $path = "rm_movies";
        $width = 200;
        $plot = htmlentities($this->res->plot);
        $title = htmlentities($this->res->title);
        $director = htmlentities($this->res->director);
        $imdb = htmlentities($this->res->imdb);
        $trailer = htmlentities($this->res->trailer);
        $genreLinks = CRMMovie::GenreLinks($this->res->genre);
        $updated = date("y-m-d H:i", strtotime($this->res->updated));
        if (CUser::IsAuthenticated()) {
            $editLink = CRMMovieAdmin::GetEditLink($this->res->id);
            $editLink .= " | " . CRMMovieAdmin::GetDeleteLink($this->res->id);
        } else {
            $editLink = "";
        }

        $out = <<<EOD
<div class="single-movie-image">
  <img src='img.php?src=$path/{$this->res->image}&width=$width&sharpen' alt='{$title}' />
</div>
<p>{$plot}</p>
<p><strong>Director:</strong> {$director}</p>
<p><strong>Pris:</strong> {$this->res->price} SEK</p>
<div class="single-movie-imdb">
  <a href="http://www.imdb.com/title/{$imdb}">
    <img src="img.php?src=IMDb-icon.png&width=50&sharpen" alt="IMDb" />
  </a>
</div>
<div class="single-movie-trailer">
  <a href="https://www.youtube.com/watch?v={$trailer}">
    <img src="img.php?src=Youtube1.png&width=50&sharpen" alt="Trailer" />
  </a>
</div>
<div class="single-movie-genres">$genreLinks</div>
<div class="single-movie-edit"><p>$editLink</p></div>
<div class="single-movie-updated"><p>Uppdaterad: $updated</p></div>
EOD;
        return $out;
    }
}
