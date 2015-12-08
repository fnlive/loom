<?php
// Skapa en klass CMovieSearch som döljer koden för att skapa sök-formuläret
// och för att förbereda den SQL-fråga som ställs mot databasen.

/**
 *
 */
class CMovieSearch
{

    function __construct()
    {
        # code...
    }

    function outputForm()
    {
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
