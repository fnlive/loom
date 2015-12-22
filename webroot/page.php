<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');
$loom['stylesheets'][] = 'css/loom-cms.css';

// Do it and store it all in variables in the Loom container.


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);
$content = new CContent($db);
$filter = new CTextFilter();

// Get page from CContent
// If $url is not set, or non-existent, user will be redirected to 404
$url = isset($_GET['url']) ? $_GET['url'] : null;
$page = $content->GetPage($url);
$title = htmlentities($page->title, null, 'UTF-8');
$loom['title'] = $title;
$data = htmlentities($page->DATA, null, 'UTF-8');
$data = $filter->doFilter($data, $page->FILTER);
$editLink = "<a href=\"edit.php?id={$page->id}\">Redigera</a>";
$meta = "Publicerat: {$page->published} " . $editLink;

$out = <<<EOD
<article class="page">
    <header>
        <h1>{$title}</h1>
    </header>
    {$data}
    <footer class="page-meta">{$meta}</footer>
</article>
EOD;

// Gather the complete html output for page.
$loom['main'] = $out;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
