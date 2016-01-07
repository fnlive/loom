<?php
/**
 * Class to search and present result from movie database.
 */
class CRMMovieSimpleSearch
{

    /**
     * Function output html for movie title search form.
     *
     * @return string with html output.
     */
    public static function outputForm()
    {
        $out = <<<EOD
        <form method="post" action="../webroot/rm-movies.php" onsubmit="">
            <input type='text' name='title' placeholder='Sök Filmtitel' />
        </form>
EOD;
        return $out;
    }


}
