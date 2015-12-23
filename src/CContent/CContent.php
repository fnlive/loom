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
     * Example
     *
     * @param string
     * @return array
     */
     /**
      * Initialize database if table Content is not present. Add default data.
      *
      */
    public function InitDb()
    {
        // Check if Db holds correct tables, else crete them
        $params = array( );
        $query = "SELECT * FROM Content";
        $tableExists = $this->contentDb->ExecuteQuery($query, $params, false);
        if (!$tableExists) {
            // Alternative syntax that could work instead of SELECT * ...?
            // CREATE TABLE IF NOT EXISTS Content
            // But how then when to create default content.
            $query = <<<EOD
    CREATE TABLE Content
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
      deleted DATETIME

    ) ENGINE INNODB CHARACTER SET utf8
EOD;
            $res = $this->contentDb->ExecuteQuery($query, array(), false);

            // Add default content after creation of table
            $query = <<<EOD
            INSERT INTO Content (slug, url, TYPE, title, DATA, FILTER, published, created) VALUES
              ('hem', 'hem', 'page', 'Hem', "Detta är min hemsida. Den är skriven i [url=http://en.wikipedia.org/wiki/BBCode]bbcode[/url] vilket innebär att man kan formattera texten till [b]bold[/b] och [i]kursiv stil[/i] samt hantera länkar.\n\nDessutom finns ett filter 'nl2br' som lägger in <br>-element istället för \\n, det är smidigt, man kan skriva texten precis som man tänker sig att den skall visas, med radbrytningar.", 'bbcode,nl2br', NOW(), NOW()),
              ('om', 'om', 'page', 'Om', "Detta är en sida om mig och min webbplats. Den är skriven i [Markdown](http://en.wikipedia.org/wiki/Markdown). Markdown innebär att du får bra kontroll över innehållet i din sida, du kan formattera och sätta rubriker, men du behöver inte bry dig om HTML.\n\nRubrik nivå 2\n-------------\n\nDu skriver enkla styrtecken för att formattera texten som **fetstil** och *kursiv*. Det finns ett speciellt sätt att länka, skapa tabeller och så vidare.\n\n###Rubrik nivå 3\n\nNär man skriver i markdown så blir det läsbart även som textfil och det är lite av tanken med markdown.", 'markdown', NOW(), NOW()),
              ('blogpost-1', NULL, 'post', 'Välkommen till min blogg!', "Detta är en bloggpost.\n\nNär det finns länkar till andra webbplatser så kommer de länkarna att bli klickbara.\n\nhttp://dbwebb.se är ett exempel på en länk som blir klickbar.", 'link,nl2br', NOW(), NOW()),
              ('blogpost-2', NULL, 'post', 'Nu har sommaren kommit', "Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost.", 'nl2br', NOW(), NOW()),
              ('blogpost-3', NULL, 'post', 'Nu har hösten kommit', "Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost", 'nl2br', NOW(), NOW())
EOD;
            $this->contentDb->ExecuteQuery($query, array(), false);
        }
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
        $type = "";
        $data = "";
        $filter = "";
        $published = date("y-m-d H:i");

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
        $sql = '
          SELECT *
          FROM Content WHERE id = ?;
        ';
        $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($sql, array($id));
        $type = $res[0]->TYPE;
        $slug = $res[0]->slug;
        $url = $res[0]->url;
        $title = $res[0]->title;
        $data = $res[0]->DATA;
        $filter = $res[0]->FILTER;
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
        $title = isset($post['title']) ? strip_tags($post['title']) : "NULL";
        $slug = isset($post['slug']) ? strip_tags($post['slug']) : "NULL";
        $slug = empty($slug) ? null : $slug;
        $url = isset($post['url']) ? strip_tags($post['url']) : "NULL";
        $url = empty($url) ? null : $url;
        $data = isset($post['data']) ? strip_tags($post['data']) : "NULL";
        $type = isset($post['type']) ? strip_tags($post['type']) : "NULL";
        $filter = isset($post['filter']) ? strip_tags($post['filter']) : "NULL";
        $published = isset($post['published']) ? strip_tags($post['published']) : "NULL";
        return array($slug, $url, $type, $title, $data, $filter, $published, );
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
        $content = $this->Sanitize($post);
        //Save content to db
        $query = <<<EOD
        INSERT INTO Content (slug, url, TYPE, title, DATA, FILTER, published, created) VALUES
          (?, ?, ?, ?, ?, ?, ?, NOW())
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
        // Add $id to end of $content
        $content[] = $id;
        $query = <<<EOD
        UPDATE Content SET
            slug = ?,
            url = ?,
            TYPE = ?,
            title = ?,
            DATA = ?,
            FILTER = ?,
            published = ?,
            updated = NOW()
        WHERE
            id = ?
EOD;
        $this->contentDb->ExecuteQuery($query, $content, false);
        // Add error handling?
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
        $query = <<<EOD
        UPDATE Content SET
            updated = NOW(),
            deleted = NOW()
        WHERE
            id = ?
EOD;
        $this->contentDb->ExecuteQuery($query, array($id), false);
        // Todo: Add error handling?
    }

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
                $sql = 'SELECT * FROM Content WHERE published < NOW() AND (deleted > NOW() OR deleted IS NULL)';
                break;
            case 'draft':
            $out .= "<h2>Utkast</h2>";
                $sql = 'SELECT * FROM Content WHERE published > NOW() AND (deleted > NOW() OR deleted IS NULL)';
                break;
            case 'deleted':
            $out .= "<h2>Papperskorgen</h2>";
                $sql = 'SELECT * FROM Content WHERE  deleted < NOW()';
                break;
            case 'all':
            default:
            $out .= "<h2>Allt innehåll</h2>";
                $sql = 'SELECT * FROM Content';
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
                <th>Publicerad</th>
            </tr>
EOD;
        // List details for all pages and posts each in a row.
        // Show links to edit, show and erase items.
        foreach($res as $val) {
            $url = $this->getUrl($val);
            $title = htmlentities($val->title);
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
            FROM Content
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
    public function GetPosts($slug='')
    {
        $slugSql = $slug ? 'slug = ?' : '1';
        $sql = "
        SELECT *
        FROM Content
        WHERE
          type = 'post' AND
          $slugSql AND
          published <= NOW() AND
          (deleted > NOW() OR deleted IS NULL)
        ORDER BY updated DESC
        ;
        ";
        $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($sql, array($slug));
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
          FROM Content WHERE id = ?;
        ';
        $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($sql, array($id));
        if (empty($res)) {
            header("Location: 404.php");
        }
        return $res;
    }

}
