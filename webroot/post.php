<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');

// Do it and store it all in variables in the Loom container.


// Connect to a MySQL database using PHP PDO
$db = new CDatabase($loom['database']);
$content = new CContent($db);
$filter = new CTextFilter();
$loom['stylesheets'][] = 'css/loom-cms.css';

// Get posts from CContent
// If $slug is not set show all posts
// IF $slug is non-existent, user will be redirected to 404
$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$posts = $content->GetPosts($slug);
// dump($posts);

$loom['title'] = "Blogg";

$postHtml = "";
$postHtml .= "<h1>Min Blogg</h1>";
if (0==count($posts)) {
    $postHtml .= "<p>Vi hittar inga inlägg.</p>";
}
foreach ($posts as $post) {
    // dump($post);
    $title = htmlentities($post->title, null, 'UTF-8');
    $data = $filter->doFilter(htmlentities($post->DATA, null, 'UTF-8'), $post->FILTER);
    $editLink = "<a href=\"edit.php?id={$post->id}\">Redigera</a>";
    $meta = "Publicerat: {$post->published} | " . $editLink;
    $postHtml .= <<<EOD
    <section>
      <article>
        <header>
            <h2><a href="?slug={$post->slug}">{$title}</a></h2>
        </header>
        <footer>
            <div class="post-data">$data</div>
            <div class="post-meta">$meta</div>
        </footer>
        </article>
      </section>
EOD;
}
$postHtml .= '<br><a href="post.php">Visa alla inlägg</a>';

// Gather the complete html output for page.
$loom['main'] = $postHtml;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
