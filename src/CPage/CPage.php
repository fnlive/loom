<?php
/**
 * Show Page
 * Create html-output to render a page from Loom CMS
 */
class CPage
{

    public static function Get($url, &$title, $db)
    {
        $content = new CContent($db);
        $filter = new CTextFilter();

        $page = $content->GetPage($url);
        $title = htmlentities($page->title, null, 'UTF-8');
        $data = htmlentities($page->DATA, null, 'UTF-8');
        $data = $filter->doFilter($data, $page->FILTER);
        $editLink = "<a href=\"edit.php?id={$page->id}\">Redigera</a>";
        $pubDate = date("y-m-d H:i", strtotime($page->published));
        $meta = "Publicerat: {$pubDate} | " . "Författare: {$page->author} | " . $editLink;

        $out = <<<EOD
        <article class="page">
            <header>
                <h1>{$title}</h1>
            </header>
            {$data}
            <footer class="page-meta">{$meta}</footer>
        </article>
EOD;
        return $out;
    }
}
