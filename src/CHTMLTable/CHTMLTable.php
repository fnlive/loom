<?php

/**
 * Class to output result from database search as html table.
 */
class CHTMLTable
{

  private $res;

  function __construct($result)
  {
    $this->res = $result;
  }

  function output($rows, $hitsPerPage, $navigatePage)
  {
    // dump($this->res);
    // Put results into a HTML-table
    $tr = "<tr><th>Rad</th><th>Id " . orderby('id') . "</th><th>Bild</th><th>Titel " . orderby('title') . "</th><th>År " . orderby('year') . "</th><th>Genre</th></tr>";
    foreach($this->res AS $key => $val) {
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
