<?php
/**
 * Class to search and present result from movie database.
 */
class CMovieSearch
{

        /**
         * Properties
         *
         */
         private $msdb     = null;
         private $sqlOrig  = null;
         private $params   = null;
         private $groupby  = null;
         private $where    = null;
         private $title    = null;
         private $genre    = null;
         private $hits     = null;
         private $page     = null;
         private $year1    = null;
         private $year2    = null;
         private $orderby  = null;
         private $order    = null;


         /**
          * Constructor
          *
          */
        function __construct($db, $searchParams)
        {
            $this->msdb = $db;
            // Get parameters
            $this->title    = isset($searchParams['title']) ? $searchParams['title'] : null;
            $this->genre    = isset($searchParams['genre']) ? $searchParams['genre'] : null;
            $this->hits     = isset($searchParams['hits'])  ? $searchParams['hits']  : 8;
            $this->page     = isset($searchParams['page'])  ? $searchParams['page']  : 1;
            $this->year1    = isset($searchParams['year1']) && !empty($searchParams['year1']) ? $searchParams['year1'] : null;
            $this->year2    = isset($searchParams['year2']) && !empty($searchParams['year2']) ? $searchParams['year2'] : null;
            $this->orderby  = isset($searchParams['orderby']) ? strtolower($searchParams['orderby']) : 'id';
            $this->order    = isset($searchParams['order'])   ? strtolower($searchParams['order'])   : 'asc';


            // Check that incoming parameters are valid
            is_numeric($this->hits) or die('Check: Hits must be numeric.');
            is_numeric($this->page) or die('Check: Page must be numeric.');
            is_numeric($this->year1) || !isset($year1)  or die('Check: Year must be numeric or not set.');
            is_numeric($this->year2) || !isset($year2)  or die('Check: Year must be numeric or not set.');
            in_array($this->orderby, array('id', 'title', 'year')) or die('Check: Not valid column.');
            in_array($this->order, array('asc', 'desc')) or die('Check: Not valid sort order.');
            // Not necessary to validate title and genre. Only if they are to be output to screen run through htmlentities().
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

        $genres = null;
        foreach($res as $val) {
          if($val->name == $genre) {
            $genres .= "$val->name ";
          }
          else {
            $genres .= "<a href='" . CMovieNav::getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
          }
        }
        return $genres;
    }

    private function MaxPages()
    {
        // Get max pages for current query, for navigation
        $sqlOrig = $this->sqlOrig;
        $sql = "
          SELECT
            COUNT(id) AS rows
          FROM
          (
            $sqlOrig {$this->where} $this->groupby
          ) AS Movie
        ";
        $res = $this->msdb->ExecuteSelectQueryAndFetchAll($sql, $this->params, false);
        $rows = $res[0]->rows;
        $max = ceil($rows / $this->hits);
        return array($max, $rows);
    }


    private function Search()
    {
        // Prepare the query based on incoming arguments
        // and store for use later on.
        $this->sqlOrig = '
          SELECT
            M.*,
            GROUP_CONCAT(G.name) AS genre
          FROM Movie AS M
            LEFT OUTER JOIN Movie2Genre AS M2G
              ON M.id = M2G.idMovie
            INNER JOIN Genre AS G
              ON M2G.idGenre = G.id
        ';
        $this->where    = null;
        $this->groupby  = ' GROUP BY M.id';
        $limit    = null;
        $sort     = " ORDER BY $this->orderby $this->order";
        $this->params   = array();

        // Select by title
        if($this->title) {
          $this->where .= ' AND title LIKE ?';
          $this->params[] = $this->title;
        }

        // Select by year
        if($this->year1) {
          $this->where .= ' AND year >= ?';
          $this->params[] = $this->year1;
        }
        if($this->year2) {
          $this->where .= ' AND year <= ?';
          $this->params[] = $this->year2;
        }

        // Select by genre
        if($this->genre) {
          $this->where .= ' AND G.name = ?';
          $this->params[] = $this->genre;
        }

        // Pagination
        if($this->hits && $this->page) {
          $limit = " LIMIT $this->hits OFFSET " . (($this->page - 1) * $this->hits);
        }

        // Complete the sql statement
        $this->where = $this->where ? " WHERE 1 {$this->where}" : null;
        $sql = $this->sqlOrig . $this->where . $this->groupby . $sort . $limit;
        $res = $this->msdb->ExecuteSelectQueryAndFetchAll($sql, $this->params, false);
        return $res;
    }

    /**
     * Function output html for movie search form.
     *
     * @return string with html output.
     */
    private function outputForm()
    {
        $genres = $this->outputGenreLinks($this->genre);
        $out = <<<EOD
    <form>
      <fieldset>
      <legend>Sök</legend>
      <input type=hidden name=genre value='{$this->genre}'/>
      <input type=hidden name=hits value='{$this->hits}'/>
      <input type=hidden name=page value='1'/>
      <p><label>Titel (delsträng, använd % som *): <input type='search' name='title' value='{$this->title}'/></label></p>
      <p><label>Välj genre:</label> {$genres}</p>
      <p><label>Skapad mellan åren:
          <input type='text' name='year1' value='{$this->year1}'/></label>
          -
          <label><input type='text' name='year2' value='{$this->year2}'/></label>

      </p>
      <p><input type='submit' name='submit' value='Sök'/></p>
      <p><a href='?'>Visa alla</a></p>
      </fieldset>
    </form>
EOD;
        return $out;
    }

    /**
     * Function output html for movie search form and
     * movie search results.
     *
     * @return string with html output.
     */
    public function output()
    {
        $html = "";
        // Get html for search form
        $html .= $this->outputForm();

        // Get search result from movie database
        $res = $this->Search();
        list($max, $rows) = $this->MaxPages();

        // Prepare to put results into rows for a HTML-table.
        $hitsPerPage = CMovieNav::getHitsPerPage(array(2, 4, 8), $this->hits);
        $navigatePage = CMovieNav::getPageNavigation($this->hits, $this->page, $max);
        $html .= CHTMLTable::output($res, $rows, $hitsPerPage, $navigatePage);

        return $html;
    }
}
