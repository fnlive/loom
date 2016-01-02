<?php

/**
 * Class to output result from database search as html table.
 */
class CHTMLTable
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


  public static function output($res, $rows, $hitsPerPage, $navigatePage)
  {
    // dump($res);
    // Put results into a HTML-table
    $tr = "<tr><th>Rad</th><th>Id " . CHTMLTable::orderby('id') . "</th><th>Bild</th><th>Titel " . CHTMLTable::orderby('title') . "</th><th>År " . CHTMLTable::orderby('year') . "</th><th>Genre</th></tr>";
    foreach($res AS $key => $val) {
        // Sanitize content from database before outputting in html.
        $key = htmlentities($key);
        $val->id = htmlentities($val->id);
        $val->image = htmlentities($val->image);
        $val->title = htmlentities($val->title);
        $val->YEAR = htmlentities($val->YEAR);
        $val->genre = htmlentities($val->genre);
        $tr .= "<tr><td>{$key}</td><td>{$val->id}</td><td><img width='32' height='44' src='{$val->image}' alt='{$val->title}' /></td><td>{$val->title}</td><td>{$val->YEAR}</td><td>{$val->genre}</td></tr>";
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
