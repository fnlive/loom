<?php
// Skapa en klass CMovieSearch som döljer koden för att skapa sök-formuläret
// och för att förbereda den SQL-fråga som ställs mot databasen.

/**
 * Class to generate html for movie search form
 */
class CMovieSearch
{
    /**
     * Properties
     *
     */
     private $msdb = null;

     /**
      * Constructor
      *
      */
    function __construct($db)
    {
        $this->msdb = $db;
    }

    private function outputGenreLinks($genre)
    {
        // Get all genres that are active
        $sql = '
          SELECT DISTINCT G.name
          FROM Genre AS G
            INNER JOIN Movie2Genre AS M2G
              ON G.id = M2G.idGenre
        ';
        $res = $this->msdb->ExecuteSelectQueryAndFetchAll($sql);
        // dump($res);

        $genres = null;
        foreach($res as $val) {
          if($val->name == $genre) {
            $genres .= "$val->name ";
          }
          else {
            $genres .= "<a href='" . getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
          }
        }
        return $genres;
    }

    public function outputForm($title, $year1, $year2, $hits, $genre)
    {
        $genres = $this->outputGenreLinks($genre);
        $out = <<<EOD
    <form>
      <fieldset>
      <legend>Sök</legend>
      <input type=hidden name=genre value='{$genre}'/>
      <input type=hidden name=hits value='{$hits}'/>
      <input type=hidden name=page value='1'/>
      <p><label>Titel (delsträng, använd % som *): <input type='search' name='title' value='{$title}'/></label></p>
      <p><label>Välj genre:</label> {$genres}</p>
      <p><label>Skapad mellan åren:
          <input type='text' name='year1' value='{$year1}'/></label>
          -
          <label><input type='text' name='year2' value='{$year2}'/></label>

      </p>
      <p><input type='submit' name='submit' value='Sök'/></p>
      <p><a href='?'>Visa alla</a></p>
      </fieldset>
    </form>
EOD;
        return $out;
    }
}
