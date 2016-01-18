<?php
/**
 * Class to generate html to present one/many movies
 */
class CRMMovieView
{

    function __construct()
    {

    }

    public static function output($movies)
    {
        $path = "rm_movies";
        $width = 150;
        $height = 250;

        $out = "";
        foreach ($movies as $movie) {
        $out .=<<<EOD
    <div class="movie-card">
        <a href="rm-movie.php?id={$movie->id}"><img src="img.php?src=$path/{$movie->image}&width=$width&height=$height&crop-to-fit&sharpen" alt="{$movie->title}" /></a>
        <div class="movie-title">
            <a href="rm-movie.php?id={$movie->id}">{$movie->title}</a>
        </div>
    </div>
EOD;
}

        return $out;
    }
}
