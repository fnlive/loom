<?php
/**
 * Show Blog
 * Create html-output to render Blog from Loom CMS
 */
class CBlog
{

    const EXTRACT_LENGTH = 15;

    public static function TrimText($text, $wordLimit)
    {
        if (str_word_count($text, 0) > $wordLimit) {
            $words = str_word_count($text, 2, 'åäö&;');
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$wordLimit]) . "&hellip;";
        }
        return $text;
    }

    public static function outputSinglePost($post, $abstract)
    {
        // echo __FILE__ . " : " . __LINE__ . "<br>";dump($post);
        $filter = new CTextFilter();
        $title = htmlentities($post->title, null, 'UTF-8');
        $slug = htmlentities($post->slug);
        $category = htmlentities($post->category, null, 'UTF-8');
        $data = $filter->doFilter(htmlentities($post->DATA, null, 'UTF-8'), $post->FILTER);
        if (true == $abstract) {
            $data = self::TrimText($data, self::EXTRACT_LENGTH);
            $data .= "<a href=\"?slug={$slug}\"><br>Läs mer</a>";
        }
        $user = new CUser();
        if ($user->IsAuthenticated()) {
            $editLink = " | <a href=\"edit.php?id={$post->id}\">Redigera</a>";
        } else {
            $editLink = "";
        }
        $author = htmlentities($post->author);
        $pubDate = date("y-m-d H:i", strtotime(htmlentities($post->published)));
        $categoryLink = "<a href=\"post.php?category=$category\">$category</a>";
        $meta = "Publicerat: {$pubDate} | " . "Författare: {$author} | " . "Kategori: {$categoryLink}" . $editLink;
        $out = <<<EOD
        <section>
          <article>
            <header>
                <h2><a href="?slug={$slug}">{$title}</a></h2>
            </header>
            <footer>
                <div class="post-data">$data</div>
                <div class="post-meta">$meta</div>
            </footer>
            </article>
          </section>
EOD;
        return $out;
    }

    public static function outputPostsAbstract($posts)
    {
        $out = "";
        if (0==count($posts)) {
            $out .= "<p>Vi hittar inga inlägg.</p>";
        }
        foreach ($posts as $post) {
            $out .= self::outputSinglePost($post, true);
        }
        $out .= '<p><a href="post.php">Visa alla inlägg</a></p>';

        return $out;
    }

    public static function GetByCategory($category, $db)
    {
        $content = new CContent($db);

        $posts = $content->GetPostsByCategory($category);

        $out = "";
        $out .= "<h1>Kategori $category</h1>";
        foreach ($posts as $post) {
            $out .= self::outputSinglePost($post, true);
        }
        return $out;
    }

    public static function Get($slug, $db)
    {
        $content = new CContent($db);

        $posts = $content->GetPosts($slug);
        $out = "";
        if (0==count($posts)) {
            $out .= "<p>Vi hittar inga inlägg.</p>";
        } elseif (1==count($posts)) {
            $out .= self::outputSinglePost($posts[0], false);
        } else {
            $out .= "<h1>Nyheter</h1>";
            foreach ($posts as $post) {
                $out .= self::outputSinglePost($post, true);
            }

        }
        $out .= '<p><a href="post.php">Visa alla inlägg</a></p>';
        return $out;
    }
}
