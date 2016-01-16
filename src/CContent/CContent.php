<?php
/**
 * Manage web content in database..
 *
 */
class CContent
{
    private $contentDb;

    function __construct($db)
    {
        $this->contentDb = $db;
        $this->InitDb();
    }


     /**
      * Initialize database if table Content is not present. Add default data.
      *
      */
    public function InitDb()
    {
        // Check if Db holds correct tables, else crete them
        $params = array( );
        $query = "SELECT * FROM rm_content";
        $tableExists = $this->contentDb->ExecuteQuery($query, $params, false);
        if (!$tableExists) {
            $query = <<<EOD
    CREATE TABLE rm_content
    (
      id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
      slug CHAR(80) UNIQUE,
      url CHAR(80) UNIQUE,

      TYPE CHAR(80),
      title VARCHAR(80),
      DATA TEXT,
      FILTER CHAR(80),

      published DATETIME,
      created DATETIME,
      updated DATETIME,
      deleted DATETIME,
      author TEXT,
      category VARCHAR(80)
    ) ENGINE INNODB CHARACTER SET utf8
EOD;
            $res = $this->contentDb->ExecuteQuery($query, array(), false);
            echo "Created content table.<br>";
            // Add default content after creation of table
            $query = <<<EOD
            INSERT INTO rm_content (slug, url, TYPE, title, DATA, FILTER, published, created, author, category) VALUES
            (NULL, 'om-oss', 'page', 'Om oss', '
Vi är Rental Movies
===================
Välkommen till Rental Movies, din filmleverantör på nätet med det allra bästa, största och senaste filmutbudet.

Vi arbetar både med de stora filmdistributörerna som [Warner Bros.](http://www.warnerbros.com/), [Walt Disney](https://thewaltdisneycompany.com/), [Sony Pictures](http://www.sonypictures.com/), [20th Century Fox](http://www.foxmovies.com/), [Paramount Pictures](http://www.paramount.com/) såväl som mindre indie-distributörer.

Vi gillar film och det märks i vårt kvalitativa utbud. Titta gärna runt bland våra [filmer](rm-movies.php) och se en film redan ikväll. Vi bjuder på första filmen.

Titta gärna in på våra [Nyheter](post.php) för att se vad som händer hos oss. Vi har alltid något nytt intressant på gång.

![RM Rental Movies](img.php?src=rm-movie-theatre-screen.png&width=650)

', 'markdown', NOW(), NOW(), 'admin', ''),

            ('tavla-kasta-tarning-vinn-en-film', NULL, 'post', 'Tävla och vinn en film', '
Var med i vår tävling och vinn en film. Pröva vårt [tärningsspel](dice-100.php). Kasta tärning och kom först till 100. Passa på att utmana dina kompisar så kan de också vinna en film.
', 'markdown', NOW(), NOW(), 'admin', 'Nyheter'),

            ('nya-filmer-december', NULL, 'post', 'Nya filmer under december', '
Under december har vi fått in ett antal nyheter i vårt film-sortiment. T.ex. så kan du se den fint animerade japanska filmen [Min granne Totoro](rm-movie.php?id=7). Detta är en fantastisk film att njuta av tillsammans med familjen under jul-helgen.

![Min Granne Totoro](img.php?src=rm_movies/Tonari-no-Totoro.jpg&width=100)

Är du mer road av skräck rekommenderar vi [From Dusk Till Dawn](rm-movie.php?id=5) med bl.a. George Clooney, Quentin Tarantino och Harvey Keitel i huvudrollerna.

![From Dusk Till Dawn](img.php?src=rm_movies/from-dusk-till-dawn.jpg&width=100)
', 'markdown', NOW(), NOW(), 'admin', 'Nyheter'),

            ('klassisk-western', NULL, 'post', 'Klassisk western', 'Skriv en texten här...', 'markdown', NOW(), NOW(), 'admin', 'Recension'),

            ('recension-av-veckans-skrackis', NULL, 'post', 'Recension av veckans skräckis', 'Skriv en texten här...', 'markdown', NOW(), NOW(), 'admin', 'Nyheter'),

            ('tavla-2', NULL, 'post', 'Tävla, kasta tärning och vinn en film', 'Skriv en texten här...', 'markdown', NOW(), NOW(), 'admin', 'Nyheter'),

            ('tavla-3', NULL, 'post', 'Tävla, kasta tärning och vinn en film', 'Skriv en texten här...', 'markdown', NOW(), NOW(), 'admin', 'Nyheter'),

            ('rental-movies-startar', NULL, 'post', 'Rental Movies startar filmuthyrning på nätet', '
Nu startar Rental Movies, din bästa filmleverantör på nätet. Här hittar du kvalitetsfilmer både från de stora filmdistributörerna såväl från de mindre indie-filmarna.

Vi som jobbar här älskar film och det märks i vårt utbud. Bläddra igenom bland våra [filmer](rm-movies.php) så ser du vad vi menar. Skulle det vara något som du saknar så säg till. Skicka ett mail till [Rental Movies](mailto:gunnar@rentalmovies.se).
', 'markdown', NOW(), NOW(), 'admin', 'Nyheter'),

              ('hem', 'hem', 'page', 'Hem', "Detta är min hemsida. Den är skriven i [url=http://en.wikipedia.org/wiki/BBCode]bbcode[/url] vilket innebär att man kan formattera texten till [b]bold[/b] och [i]kursiv stil[/i] samt hantera länkar.\n\nDessutom finns ett filter 'nl2br' som lägger in <br>-element istället för \\n, det är smidigt, man kan skriva texten precis som man tänker sig att den skall visas, med radbrytningar.", 'bbcode,nl2br', NOW(), NOW(), 'admin', 'Nyheter'),
              ('om', 'om', 'page', 'Om', "Detta är en sida om mig och min webbplats. Den är skriven i [Markdown](http://en.wikipedia.org/wiki/Markdown). Markdown innebär att du får bra kontroll över innehållet i din sida, du kan formattera och sätta rubriker, men du behöver inte bry dig om HTML.\n\nRubrik nivå 2\n-------------\n\nDu skriver enkla styrtecken för att formattera texten som **fetstil** och *kursiv*. Det finns ett speciellt sätt att länka, skapa tabeller och så vidare.\n\n###Rubrik nivå 3\n\nNär man skriver i markdown så blir det läsbart även som textfil och det är lite av tanken med markdown.", 'markdown', NOW(), NOW(), 'admin', 'Recensioner'),
              ('blogpost-1', NULL, 'post', 'Välkommen till min blogg!', "Detta är en bloggpost.\n\nNär det finns länkar till andra webbplatser så kommer de länkarna att bli klickbara.\n\nhttp://dbwebb.se är ett exempel på en länk som blir klickbar.", 'link,nl2br', NOW(), NOW(), 'doe', 'Nyheter'),
              ('blogpost-2', NULL, 'post', 'Nu har sommaren kommit', "Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost.", 'nl2br', NOW(), NOW(), 'admin', 'Recensioner'),
              ('blogpost-3', NULL, 'post', 'Nu har hösten kommit', "Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost", 'nl2br', NOW(), NOW(), 'doe', 'Nyheter')
EOD;
            $this->contentDb->ExecuteQuery($query, array(), false);
            echo "Created default content.<br>";
        }
    }

    public function Reset()
    {
        $query = "
            DROP TABLE rm_content;
        ";
        $res = $this->contentDb->ExecuteQuery($query, array(), false);
        $this->InitDb();
    }

    /**
     * Create a link to the content, based on its type.
     *
     * @param object $content to link to.
     * @return string with url to display content.
     */
     public function getUrl($content) {
         switch($content->TYPE) {
            case 'page': return "page.php?url={$content->url}"; break;
            case 'post': return "post.php?slug={$content->slug}"; break;
            default: return null; break;
        }
    }

    /**
     * Return html form to create content.
     *
     * @return string  with html form
     */
    public function getCreateContentForm()
    {
        if (!CUser::IsAuthenticated()) {
            $out = "Du måste logga in för att skapa innehåll";
            $out .= CUser::LoginForm();
            return $out;
        }
        $data = "";
        // Set markdown as default filter
        $filter = "markdown";
        $published = date("y-m-d H:i");
        $author = CUser::GetAcronym();
        $out = <<<EOD
<form method=post>
    <fieldset>
    <legend>Skapa innehåll</legend>
    <p><label>Titel:<br/><input type='text' name='title' value='' required/></label></p>
    <p><label>Slug:<br/><input type='text' name='slug' value=''/></label></p>
    <p><label>Url:<br/><input type='text' name='url' value=''/></label></p>
    <p><label>Text:<br/><textarea name='data'>{$data}</textarea></label></p>
    <p><label>Type:<br/></label>
        <input type="radio" name="type" value="page"> Page<br>
        <input type="radio" name="type" value="post" checked="checked"> Post<br>
    </p>
    <p><label>Filter:<br/><input type='text' name='filter' value='{$filter}'/></label></p>
    <p><label>Författare:<br/><input type='text' name='author' value='{$author}'/></label></p>
    <p><label>Kategori:<br/><input type='text' name='category' value=''/></label></p>
    <p><label>Publiseringsdatum:<br/><input type='text' name='published' value='{$published}'/></label></p>
    <p class=buttons>
        <input type='submit' name='save' value='Spara'/>
        <input type='reset' value='Återställ'/>
    </p>
    </fieldset>
</form>
EOD;
        return $out;
    }

    /**
     * Return html form to edit content.
     *
     * @return string  with html form
     */
    public function getEditContentForm($id)
    {
        if (!CUser::IsAuthenticated()) {
            $out = "Du måste logga in för att redigera innehåll";
            $out .= CUser::LoginForm();
            return $out;
        }
        $sql = '
          SELECT *
          FROM rm_content WHERE id = ?;
        ';
        $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($sql, array($id), false);
        if (empty($res)) {
            $out = "Innehåll med <strong>id $id</strong> finns inte. Vill du skapa en ny sida eller blogg post?";
            return $out;
        }
        $type = $res[0]->TYPE;
        $slug = $res[0]->slug;
        $url = $res[0]->url;
        $title = $res[0]->title;
        $data = $res[0]->DATA;
        $filter = $res[0]->FILTER;
        $author = $res[0]->author;
        $category = $res[0]->category;
        $published = $res[0]->published;
        if ("page"==$type) {
            $pageChecked = 'checked="checked"';
            $postChecked = '';
        } else {
            $postChecked = 'checked="checked"';
            $pageChecked = '';
        }
        $out = <<<EOD
<form method=post>
    <fieldset>
    <legend>Uppdatera innehåll</legend>
    <input type=hidden name=id value='{$id}'/>
    <p><label>Titel:<br/><input type='text' name='title' value='$title' required/></label></p>
    <p><label>Slug:<br/><input type='text' name='slug' value='$slug'/></label></p>
    <p><label>Url:<br/><input type='text' name='url' value='$url'/></label></p>
    <p><label>Text:<br/><textarea name='data'>{$data}</textarea></label></p>
    <p><label>Type:<br/></label>
        <input type="radio" name="type" value="page" $pageChecked> Page<br>
        <input type="radio" name="type" value="post" $postChecked> Post<br>
    </p>
    <p><label>Filter:<br/><input type='text' name='filter' value='{$filter}'/></label></p>
    <p><label>Författare:<br/><input type='text' name='author' value='{$author}'/></label></p>
    <p><label>Kategori:<br/><input type='text' name='category' value='{$category}'/></label></p>
    <p><label>Publiseringsdatum:<br/><input type='text' name='published' value='{$published}'/></label></p>
    <p class=buttons>
        <input type='submit' name='update' value='Uppdatera'/>
        <input type='reset' value='Återställ'/>
    </p>
    </fieldset>
</form>
EOD;
        return $out;
    }

    /**
     * Return html form to create content.
     *
     * @param array $post containing content to be saved
     * @return array sanitized content
     */
    private function Sanitize($post)
    {
        //Do not sanitize title and data. Allow html tags in them.
        // Instead, remove vulnarable code with htmlentities before display.
        // $post['title'] = isset($post['title']) ? strip_tags($post['title']) : "NULL";
        $post['title'] = isset($post['title']) ? $post['title'] : null;
        $post['slug'] = isset($post['slug']) ? strip_tags($post['slug']) : null;
        // $post['slug'] = empty($post['slug']) ? null : $post['slug'];
        $post['url'] = isset($post['url']) ? strip_tags($post['url']) : null;
        // $post['url'] = empty($post['url']) ? null : $post['url'];
        // $post['data'] = isset($post['data']) ? strip_tags($post['data']) : null;
        $post['data'] = isset($post['data']) ? $post['data'] : null;
        $post['type'] = isset($post['type']) ? strip_tags($post['type']) : null;
        $post['filter'] = isset($post['filter']) ? strip_tags($post['filter']) : null;
        $post['published'] = isset($post['published']) ? strip_tags($post['published']) : null;
        $post['author'] = isset($post['author']) ? strip_tags($post['author']) : null;
        return $post;
    }

    /**
     * Create a slug of a string, to be used as url.
     *
     * @param string $str the string to format as slug.
     * @return str the formatted slug.
     */
    private function slugify($str) {
      $str = mb_strtolower(trim($str));
      $str = str_replace(array('å','ä','ö'), array('a','a','o'), $str);
      $str = preg_replace('/[^a-z0-9-]/', '-', $str);
      $str = trim(preg_replace('/-+/', '-', $str), '-');
      return $str;
    }

    /**
     * Save new created content to database
     *
     * @param array $post containing content to be saved
     * @return void
     */
    public function Save($post)
    {
        // Slugify title if slug or url is empty
        if(empty($post['slug'])) {
            $post['slug'] = $this->slugify($post['title']);
        }
        if(empty($post['url'])) {
            $post['url'] = $this->slugify($post['title']);
        }
        // Sanitize data
        $post = $this->Sanitize($post);
        // Prepare array for storage in database
        $content = array($post['slug'], $post['url'], $post['type'], $post['title'], $post['data'], $post['filter'], $post['published'], $post['author'], $post['category']);
        //Save content to db
        $query = <<<EOD
        INSERT INTO rm_content (slug, url, TYPE, title, DATA, FILTER, published, author, category, created) VALUES
          (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
EOD;
        $this->contentDb->ExecuteQuery($query, $content, false);

        // Send user to edit page if user wants to update item
        $id = $this->contentDb->LastInsertId();
        header("Location: edit.php?id=$id");
    }

    /**
     * Update content in database
     *
     * @param array $post containing content to be saved
     * @return void
     */
    public function Update($content)
    {
        // Sanitize $id first.
        $id = isset($_POST['id']) ? strip_tags($_POST['id']) : "NULL";
        is_numeric($id) or die('Check: Id must be numeric.');
        // Sanitize $content
        $content = $this->Sanitize($content);
        $content = array($content['slug'], $content['url'], $content['type'], $content['title'], $content['data'], $content['filter'], $content['published'], $content['author'], $content['category']);
        // Add $id to end of $content
        $content[] = $id;
        $query = <<<EOD
        UPDATE rm_content SET
            slug = ?,
            url = ?,
            TYPE = ?,
            title = ?,
            DATA = ?,
            FILTER = ?,
            published = ?,
            author = ?,
            category = ?,
            updated = NOW()
        WHERE
            id = ?
EOD;
        $this->contentDb->ExecuteQuery($query, $content, false);
        header("Location: edit.php?id=$id");
    }

    /**
     * Delete content in database
     *
     * @param integer $id for content to be deleted
     * @return void
     */
    public function Delete($id)
    {
        $out = "";
        if (!CUser::IsAuthenticated()) {
            $out .= "Du måste logga in för att redigera innehåll";
            $out .= CUser::LoginForm();
            return $out;
        }
        $query = <<<EOD
        UPDATE rm_content SET
            updated = NOW(),
            deleted = NOW()
        WHERE
            id = ?
EOD;
        $success = $this->contentDb->ExecuteQuery($query, array($id), false);
        if ($success) {
            $res = $this->GetItem($id);
            $url = $res[0]->url;
            $out .= "<p>Du har raderat <a href='{$res[0]->url}'>{$res[0]->title}</a>.</p>";
        } else {
            $out .= "Det gick inte att radera innehåll med id $id.";
        }
        return $out;
    }

// TODO: Undelete funktion?

    /**
     * Generate html for page to administrate content
     *
     * @param string $show what content to display
     * @return string generated html
     */
    public function ShowItems($show='all')
    {
        $out = "";
        switch ($show) {
            case 'published':
                $out .= "<h2>Publicerade</h2>";
                $sql = 'SELECT * FROM rm_content WHERE published < NOW() AND (deleted > NOW() OR deleted IS NULL)';
                break;
            case 'draft':
            $out .= "<h2>Utkast</h2>";
                $sql = 'SELECT * FROM rm_content WHERE published > NOW() AND (deleted > NOW() OR deleted IS NULL)';
                break;
            case 'deleted':
            $out .= "<h2>Papperskorgen</h2>";
                $sql = 'SELECT * FROM rm_content WHERE  deleted < NOW()';
                break;
            case 'all':
            default:
            $out .= "<h2>Allt innehåll</h2>";
                $sql = 'SELECT * FROM rm_content';
                break;
        }
        $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($sql, array(), false);
        $out .= <<<EOD
        <table class="content-table">
            <tr>
                <th>Titel</th>
                <th>Typ</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Kat</th>
                <th>Författare</th>
                <th>Publicerad</th>
            </tr>
EOD;
        // List details for all pages and posts each in a row.
        // Show links to edit, show and erase items.
        foreach($res as $val) {
            $url = $this->getUrl($val);
            $title = htmlentities($val->title);
            $category = htmlentities($val->category);
            $pubTime = strtotime($val->published);
            $pubDate = date("y-m-d", strtotime($val->published));
            $delTime = strtotime($val->deleted);
            $delDate = date("y-m-d H:i", strtotime($val->deleted));
            $time = time();
            // Calculate status: published, draft, deleted
            if ((!empty($delTime)) && ($delTime < $time) ) {
                $status = "Raderad";
                // echo "Raderad";
            } elseif ($pubTime > $time) {
                $status = "Utkast";
                // echo "Utkast";
            } elseif ($pubTime < $time) {
                $status = "Publ";
                // echo "Publ";
            } else {
                $status = "Okänd";
            }
            // Todo: create recovery link for deleted items.
            $out .= <<<EOD
            <tr>
                <td>{$title}<br><a href="edit.php?id={$val->id}">Redigera</a> | <a href="{$url}">Visa</a> | <a href="delete.php?id={$val->id}">Radera</a></td>
                <td>{$val->TYPE}</td>
                <td>{$val->slug}</td>
                <td>$status</td>
                <td>$category</td>
                <td>$val->author</td>
                <td>{$pubDate}</td>
            </tr>
EOD;
        }
        $out .= "</table>";
        $cnt = count($res);
        $poster = (1==$cnt) ? "post" : "poster";
        $out .= "<p>Totalt $cnt $poster.</p>";
        return $out;
    }

    /**
     * Get Page content
     *
     * @param string $url of page
     * @return array content of page
     */
    public function GetPage($url)
    {
        $sql = '
            SELECT *
            FROM rm_content
            WHERE
            type = \'page\' AND
            url = ? AND
            published <= NOW();
        ';
        $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($sql, array($url));
        if (empty($res)) {
            header("Location: 404.php");
            // die("This is not the page you are looking for...");
        }
        return $res[0];
    }

    /**
     * Get Post content
     *
     * @param string $url of page
     * @return array of arrays with content of posts
     */
    public function GetPosts($slug='', $numReturnRows=null)
    {
        $slugSql = $slug ? 'slug = ?' : '1';
        $sqlNumReturnRows = $numReturnRows ? "LIMIT $numReturnRows" : '';
        $sql = "
        SELECT *
        FROM rm_content
        WHERE
          type = 'post' AND
          $slugSql AND
          published <= NOW() AND
          (deleted > NOW() OR deleted IS NULL)
        ORDER BY updated DESC
        $sqlNumReturnRows
        ;
        ";
        $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($sql, array($slug), false);
        if (empty($res)) {
            header("Location: 404.php");
        }
        return $res;
    }

        /**
         * Get Post content
         *
         * @param string $url of page
         * @return array of arrays with content of posts matching category
         */
        public function GetPostsByCategory($category='')
        {
            $catSql = $category ? 'category = ?' : '1';
            $sql = "
            SELECT *
            FROM rm_content
            WHERE
              type = 'post' AND
              $catSql AND
              published <= NOW() AND
              (deleted > NOW() OR deleted IS NULL)
            ORDER BY updated DESC
            ;
            ";
            $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($sql, array($category));
            if (empty($res)) {
                header("Location: 404.php");
            }
            return $res;
        }

    /**
     * Get content item
     *
     * @param string $id of item
     * @return array content of item
     */
    public function GetItem($id='')
    {
        $sql = '
          SELECT *
          FROM rm_content WHERE id = ?;
        ';
        $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($sql, array($id));
        if (empty($res)) {
            header("Location: 404.php");
        }
        return $res;
    }

}
