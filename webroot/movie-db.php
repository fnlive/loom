<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.

// Add style for dice-100
// $loom['stylesheets'][] = 'css/dice-100.css';

$loom['title'] = "movieDb";

$loom['inlinestyle'] = "
.orderby a {
  text-decoration: none;
  color: black;
}

.dbtable {

}

.dbtable table {
  width: 100%;
}

.dbtable .rows {
  text-align: right;
}

.dbtable .pages {
  text-align: center;
}

.debug {
  color: #666;
}
";


/**
 * Use the current querystring as base, modify it according to $options and return the modified query string.
 *
 * @param array $options to set/change.
 * @param string $prepend this to the resulting query string
 * @return string with an updated query string.
 */
function getQueryString($options=array(), $prepend='?') {
  // parse query string into array
  $query = array();
  parse_str($_SERVER['QUERY_STRING'], $query);

  // Modify the existing query string with new options
  $query = array_merge($query, $options);

  // Return the modified querystring
  return $prepend . htmlentities(http_build_query($query));
}



/**
 * Create links for hits per page.
 *
 * @param array $hits a list of hits-options to display.
 * @param array $current value.
 * @return string as a link to this page.
 */
function getHitsPerPage($hits, $current=null) {
  $nav = "Träffar per sida: ";
  foreach($hits AS $val) {
    if($current == $val) {
      $nav .= "$val ";
    }
    else {
      $nav .= "<a href='" . getQueryString(array('hits' => $val)) . "'>$val</a> ";
    }
  }
  return $nav;
}



/**
 * Create navigation among pages.
 *
 * @param integer $hits per page.
 * @param integer $page current page.
 * @param integer $max number of pages.
 * @param integer $min is the first page number, usually 0 or 1.
 * @return string as a link to this page.
 */
function getPageNavigation($hits, $page, $max, $min=1) {
  $nav  = ($page != $min) ? "<a href='" . getQueryString(array('page' => $min)) . "'>&lt;&lt;</a> " : '&lt;&lt; ';
  $nav .= ($page > $min) ? "<a href='" . getQueryString(array('page' => ($page > $min ? $page - 1 : $min) )) . "'>&lt;</a> " : '&lt; ';

  for($i=$min; $i<=$max; $i++) {
    if($page == $i) {
      $nav .= "$i ";
    }
    else {
      $nav .= "<a href='" . getQueryString(array('page' => $i)) . "'>$i</a> ";
    }
  }

  $nav .= ($page < $max) ? "<a href='" . getQueryString(array('page' => ($page < $max ? $page + 1 : $max) )) . "'>&gt;</a> " : '&gt; ';
  $nav .= ($page != $max) ? "<a href='" . getQueryString(array('page' => $max)) . "'>&gt;&gt;</a> " : '&gt;&gt; ';
  return $nav;
}



/**
 * Function to create links for sorting
 *
 * @param string $column the name of the database column to sort by
 * @return string with links to order by column.
 */
function orderby($column) {
  $nav  = "<a href='" . getQueryString(array('orderby'=>$column, 'order'=>'asc')) . "'>&darr;</a>";
  $nav .= "<a href='" . getQueryString(array('orderby'=>$column, 'order'=>'desc')) . "'>&uarr;</a>";
  return "<span class='orderby'>" . $nav . "</span>";
}



// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);


// Get parameters
$title    = isset($_GET['title']) ? $_GET['title'] : null;
$genre    = isset($_GET['genre']) ? $_GET['genre'] : null;
$hits     = isset($_GET['hits'])  ? $_GET['hits']  : 8;
$page     = isset($_GET['page'])  ? $_GET['page']  : 1;
$year1    = isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null;
$year2    = isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null;
$orderby  = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
$order    = isset($_GET['order'])   ? strtolower($_GET['order'])   : 'asc';


// Check that incoming parameters are valid
is_numeric($hits) or die('Check: Hits must be numeric.');
is_numeric($page) or die('Check: Page must be numeric.');
is_numeric($year1) || !isset($year1)  or die('Check: Year must be numeric or not set.');
is_numeric($year2) || !isset($year2)  or die('Check: Year must be numeric or not set.');
in_array($orderby, array('id', 'title', 'year')) or die('Check: Not valid column.');
in_array($order, array('asc', 'desc')) or die('Check: Not valid sort order.');
// Not necessary to validate title and genre. Only if they are to be output to screen.

// debug database
// $sql = 'select * from Movie WHERE title LIKE "%Kopps%";';
// $res = $db->ExecuteSelectQueryAndFetchAll($sql);
// echo "Dump movie table: </br>";
// dump($res);

// Get all genres that are active
$sql = '
  SELECT DISTINCT G.name
  FROM Genre AS G
    INNER JOIN Movie2Genre AS M2G
      ON G.id = M2G.idGenre
';
$res = $db->ExecuteSelectQueryAndFetchAll($sql);
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


// Prepare the query based on incoming arguments
$sqlOrig = '
  SELECT
    M.*,
    GROUP_CONCAT(G.name) AS genre
  FROM Movie AS M
    LEFT OUTER JOIN Movie2Genre AS M2G
      ON M.id = M2G.idMovie
    INNER JOIN Genre AS G
      ON M2G.idGenre = G.id
';
$where    = null;
$groupby  = ' GROUP BY M.id';
$limit    = null;
$sort     = " ORDER BY $orderby $order";
$params   = array();

// Select by title
if($title) {
  $where .= ' AND title LIKE ?';
  $params[] = $title;
}

// Select by year
if($year1) {
  $where .= ' AND year >= ?';
  $params[] = $year1;
}
if($year2) {
  $where .= ' AND year <= ?';
  $params[] = $year2;
}

// Select by genre
if($genre) {
  $where .= ' AND G.name = ?';
  $params[] = $genre;
}

// Pagination
if($hits && $page) {
  $limit = " LIMIT $hits OFFSET " . (($page - 1) * $hits);
}

// Complete the sql statement
$where = $where ? " WHERE 1 {$where}" : null;
$sql = $sqlOrig . $where . $groupby . $sort . $limit;
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);


// Put results into a HTML-table
$tr = "<tr><th>Rad</th><th>Id " . orderby('id') . "</th><th>Bild</th><th>Titel " . orderby('title') . "</th><th>År " . orderby('year') . "</th><th>Genre</th></tr>";
foreach($res AS $key => $val) {
    $tr .= "<tr><td>{$key}</td><td>{$val->id}</td><td><img width='32' height='44' src='{$val->image}' alt='{$val->title}' /></td><td>{$val->title}</td><td>{$val->YEAR}</td><td>{$val->genre}</td></tr>";
}



// Get max pages for current query, for navigation
$sql = "
  SELECT
    COUNT(id) AS rows
  FROM
  (
    $sqlOrig $where $groupby
  ) AS Movie
";
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);
$rows = $res[0]->rows;
$max = ceil($rows / $hits);



// Do it and store it all in variables in the Anax container.
$loom['title'] = "Visa filmer med sökalternativ kombinerade";

$hitsPerPage = getHitsPerPage(array(2, 4, 8), $hits);
$navigatePage = getPageNavigation($hits, $page, $max);
$sqlDebug = $db->Dump();

$loom['main'] = <<<EOD
<h1>{$loom['title']}</h1>

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

<div class='dbtable'>
  <div class='rows'>{$rows} träffar. {$hitsPerPage}</div>
  <table>
  {$tr}
  </table>
  <div class='pages'>{$navigatePage}</div>
</div>


EOD;
// <div class=debug>{$sqlDebug}</div>


// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
