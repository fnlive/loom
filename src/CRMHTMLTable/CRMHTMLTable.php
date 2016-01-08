<?php

/**
 * Class to output result from database search as html table.
 */
class CRMHTMLTable
{


  /**
   * Function to create links for sorting
   *
   * @param string $column the name of the database column to sort by
   * @return string with links to order by column.
   */
  private static function orderby($column) {
    $nav  = "<a href='" . CMovieNav::getQueryString(array('orderby'=>$column, 'order'=>'asc')) . "'>&darr;</a>";
    $nav .= "<a href='" . CMovieNav::getQueryString(array('orderby'=>$column, 'order'=>'desc')) . "'>&uarr;</a>";
    return "<span class='orderby'>" . $nav . "</span>";
  }


  public static function output($res, $rows, $hitsPerPage, $navigatePage, $genresAllMovies)
  {
    // Put results into a HTML-table
    $tr = "<tr><th>Bild</th><th>Titel " . CRMHTMLTable::orderby('title') . "</th><th>År " . CRMHTMLTable::orderby('year') . "</th><th>Genre</th></tr>";
    // Set image data
    $path = "rm_movies";
    $width = 100;
    foreach($res AS $key => $val) {
        // only show edit/delete link when authenticated.
        $user = new CUser();
        // TODO: IsAuthenticated as static function?
        if ($user->IsAuthenticated()) {
            $editLink = CRMMovieAdmin::GetEditLink($val->id);
            $editLink .= " | " . CRMMovieAdmin::GetDeleteLink($val->id);
        } else {
            $editLink = "";
        }
        // Sanitize content from database before outputting in html.
        $val->id = htmlentities($val->id);
        $val->image = htmlentities($val->image);
        $val->title = htmlentities($val->title);
        $val->year = htmlentities($val->year);
        // $val->genre = htmlentities($val->genre);
        $genres = htmlentities($genresAllMovies[$val->id]);
        $genreLinks = CRMMovie::GenreLinks($genres);
        $tr .= <<<EOD
        <tr>
        <td>
        <a href="rm-movie.php?id={$val->id}">
          <img src='img.php?src=$path/{$val->image}&width=$width&sharpen' alt='{$val->title}' />
        </a>
        </td>
        <td>
        <a href="rm-movie.php?id={$val->id}">{$val->title}</a>
        <p>$editLink</p>
        </td>
        <td>{$val->year}</td>
        <td>{$genreLinks}</td></tr>
EOD;
    }
    $table = <<<EOD
  <div class='dbtable'>
    <div class='rows'>{$rows} träffar. {$hitsPerPage}</div>
      <table>
      {$tr}
      </table>
    <div class='pages'>{$navigatePage}</div>
  </div>
EOD;
    return $table;
  }
}
