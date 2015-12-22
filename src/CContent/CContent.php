<?php
/**
 * Manage web content in database..
 *
 */
/**
* Tabellstrukturen i databasen skall ha minst samma funktionalitet som i den nämnda guiden.

* Klassen skall själv initiera tabellstrukturen i databasen. Låt alltså klassen ha en metod som kan skapa tabellerna.

* Det skall gå att lägga till, redigera och ta bort innehåll.

* Bygg de sidkontroller som behövs för att exemplet skall fungera.

*Draft:
* Konstruktor för att skapa/lagra CDataBase objekt.
* Metod för att skapa databasstruktur och ev lägga in dummy-innehåll.
*    Om tabbeller inte finns, skapa dem.
* Metod för att generera html för formulär för att skapa content.
* Metod för att spara innehållet från formuläret i databasen.
* Metod för att editera befintligt innehåll
* Metod för att radera innehåll

* Properties:
* contentDb CDataBase
*

* Flöde från page-controller create.php
***************************************
* ????Kontrollera om databas-tabeller finns, om inte, skapa dem. lägg i CCONTENT::constructor.
* Skriv ut formulär, posta det.
* Fånga upp om formulärt postats.
*   Om så, validera vissa fält, te.x. datum, skriv in dem i tabellen.
*
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

    public function InitDb()
    {
        // Check if Db holds correct tables, else crete htem
        $params = array( );
        $query = <<<EOD
SELECT * FROM Content
EOD;
        // Debug test to see if we find table content.
        // $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($query);
        // dump($res);
        // echo "Testing if Content table exists... ";
        $tableExists = $this->contentDb->ExecuteQuery($query, $params, false);
        if (!$tableExists) {
            echo "Creating Content table... ";
            $params = array( );
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
            echo "Close to create... ";
            $res = $this->contentDb->ExecuteQuery($query, $params, false);

            // Add default content after creation of table
            $params = array( );
            $query = <<<EOD
            INSERT INTO Content (slug, url, TYPE, title, DATA, FILTER, published, created) VALUES
              ('hem', 'hem', 'page', 'Hem', "Detta är min hemsida. Den är skriven i [url=http://en.wikipedia.org/wiki/BBCode]bbcode[/url] vilket innebär att man kan formattera texten till [b]bold[/b] och [i]kursiv stil[/i] samt hantera länkar.\n\nDessutom finns ett filter 'nl2br' som lägger in <br>-element istället för \\n, det är smidigt, man kan skriva texten precis som man tänker sig att den skall visas, med radbrytningar.", 'bbcode,nl2br', NOW(), NOW()),
              ('om', 'om', 'page', 'Om', "Detta är en sida om mig och min webbplats. Den är skriven i [Markdown](http://en.wikipedia.org/wiki/Markdown). Markdown innebär att du får bra kontroll över innehållet i din sida, du kan formattera och sätta rubriker, men du behöver inte bry dig om HTML.\n\nRubrik nivå 2\n-------------\n\nDu skriver enkla styrtecken för att formattera texten som **fetstil** och *kursiv*. Det finns ett speciellt sätt att länka, skapa tabeller och så vidare.\n\n###Rubrik nivå 3\n\nNär man skriver i markdown så blir det läsbart även som textfil och det är lite av tanken med markdown.", 'markdown', NOW(), NOW()),
              ('blogpost-1', NULL, 'post', 'Välkommen till min blogg!', "Detta är en bloggpost.\n\nNär det finns länkar till andra webbplatser så kommer de länkarna att bli klickbara.\n\nhttp://dbwebb.se är ett exempel på en länk som blir klickbar.", 'link,nl2br', NOW(), NOW()),
              ('blogpost-2', NULL, 'post', 'Nu har sommaren kommit', "Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost.", 'nl2br', NOW(), NOW()),
              ('blogpost-3', NULL, 'post', 'Nu har hösten kommit', "Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost", 'nl2br', NOW(), NOW())
EOD;
            $this->contentDb->ExecuteQuery($query, $params, false);
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

    public function getCreateContentForm()
    {
        $type = "";
        $data = "";
        $filter = "";
        $published = date("y-m-d H:i");

        // Todo Fixa input fält date etc. validering?
        // dropdown, radio buttons, ...
        $out = <<<EOD
<form method=post>
    <fieldset>
    <legend>Skapa innehåll</legend>
    <p><label>Titel:<br/><input type='text' name='title' value='' required/></label></p>
    <p><label>Slug:<br/><input type='text' name='slug' value=''/></label></p>
    <p><label>Url:<br/><input type='text' name='url' value=''/></label></p>
    <p><label>Text:<br/><textarea name='data'>{$data}</textarea></label></p>
    <p><label>Type:<br/>
        <input type="radio" name="type" value="page"> Page<br>
        <input type="radio" name="type" value="post" checked="checked"> Post<br>
    </label></p>
    <p><label>Filter:<br/><input type='text' name='filter' value='{$filter}'/></label></p>
    <p><label>Publiseringsdatum:<br/><input type='date' name='published' value='{$published}'/></label></p>
    <p class=buttons>
        <input type='submit' name='save' value='Spara'/>
        <input type='reset' value='Återställ'/>
    </p>
    </fieldset>
</form>
EOD;
        return $out;
    }

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
    <p><label>Titel:<br/><input type='text' name='title' value=$title required/></label></p>
    <p><label>Slug:<br/><input type='text' name='slug' value='$slug'/></label></p>
    <p><label>Url:<br/><input type='text' name='url' value='$url'/></label></p>
    <p><label>Text:<br/><textarea name='data'>{$data}</textarea></label></p>
    <p><label>Type:<br/>
        <input type="radio" name="type" value="page" $pageChecked> Page<br>
        <input type="radio" name="type" value="post" $postChecked> Post<br>
    </label></p>
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

    public function Save($content)
    {
        //Save content to db
        // echo "Saving post... ";
        $query = <<<EOD
        INSERT INTO Content (slug, url, TYPE, title, DATA, FILTER, published, created) VALUES
          (?, ?, ?, ?, ?, ?, ?, NOW())
EOD;
        $this->contentDb->ExecuteQuery($query, $content, false);
        // header('Location: create.php');
    }

    public function Update($id, $content)
    {
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
        // header('Location: create.php');

    }

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
        // header('Location: create.php');

    }


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
                <th>Title</th>
                <th>Typ</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Publish date</th>
            </tr>
EOD;
        // List details for all pages and posts each in a row.
        // Show links to edit, show and erase items.
        foreach($res as $val) {
            $url = $this->getUrl($val);
            $title = htmlentities($val->title);
            // Calculate status: published, draft, deleted
            // if ($val->deleted < time()) {
            //     $time = time();
            //     $date = date("y-m-d H:i");
            //     echo "Deleted: {$val->deleted}, Time; $time, Date: $date<br>";
            //     // $status =
            // }
            $out .= <<<EOD
            <tr>
                <td>{$title}<br><a href="edit.php?id={$val->id}">Redigera</a> | <a href="{$url}">Visa</a> | <a href="delete.php?id={$val->id}">Radera</a></td>
                <td>{$val->TYPE}</td>
                <td>{$val->slug}</td>
                <td></td>
                <td>{$val->published}</td>
            </tr>
EOD;
        }
        $out .= "</table>";
        $cnt = count($res);
        $poster = (1==$cnt) ? "post" : "poster";
        $out .= "<p>Totalt $cnt $poster.</p>";
        return $out;
    }

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
            die("This is not the page you are looking for...");
            // Todo: redirect with header to 404 page
        }
        return $res[0];
    }

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
        return $res;
    }

    public function GetItem($id='')
    {
        $sql = '
          SELECT *
          FROM Content WHERE id = ?;
        ';
        $res = $this->contentDb->ExecuteSelectQueryAndFetchAll($sql, array($id));
        return $res;
    }

}
