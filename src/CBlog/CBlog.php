<?php
/**
 * Show Blog
 * Create html-output to render Blog from Loom CMS
 */
class CBlog
{

    public static function Get($slug, $db)
    {
        $content = new CContent($db);
        $filter = new CTextFilter();

        $posts = $content->GetPosts($slug);
        // dump($posts);
        $out = "";
        $out .= "<h1>Min Blogg</h1>";
        if (0==count($posts)) {
            $out .= "<p>Vi hittar inga inlägg.</p>";
        }
        foreach ($posts as $post) {
            // dump($post);
            $title = htmlentities($post->title, null, 'UTF-8');
            $data = $filter->doFilter(htmlentities($post->DATA, null, 'UTF-8'), $post->FILTER);
            $editLink = "<a href=\"edit.php?id={$post->id}\">Redigera</a>";
            $meta = "Publicerat: {$post->published} | " . $editLink;
            $out .= <<<EOD
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
        $out .= '<p><a href="post.php">Visa alla inlägg</a></p>';

        return $out;
    }
}
