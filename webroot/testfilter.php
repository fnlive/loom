<?php
/**
 * This is a loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');


// Prepare the content
$html = <<<EOD
Detta är ett exempel på markdown
=================================

En länk till [Markdowns hemsida](http://daringfireball.net/projects/markdown/).

EOD;

// Filter the content
$filter = new CTextFilter();
$html = $filter->doFilter($html, "markdown");

$bbcodetest = <<<EOD
Detta är BBCode. Detta är [b]bold[/b]. Så här skrivs [i]italics[/i]. Och så [u]underline[/u]. En bild [img]http://localhost/oophp/kmom05/loom/webroot/img/Loom-icon-transp-x130.png[/img]. Och sist en hyperlänk till [url=http://www.bbcode.org/]BBCode.org[/url].
EOD;
$html .= $filter->doFilter($bbcodetest, 'bbcode');

$linktest = <<<EOD
Min url är klickbar: https://github.com/fnlive/loom
EOD;
// Result from filter: <a href='https://github.com/fnlive/loom'>https://github.com/fnlive/loom</a>
$html .= $filter->doFilter($linktest, 'clickable');

$newlinetest = <<<EOD

Detta är en rad
och här kommer en ny.
EOD;
$html .= CTextFilter::nl2br($newlinetest);

$markdowntest = <<<EOD
Test av markdowntest
====================
**Markdown** är ett text-till-html konverteringsverktyg.
Specifikation
-------------
Läs [Markdown-specifikationen](https://daringfireball.net/projects/markdown/basics).
EOD;
$html .= CTextFilter::markdown($markdowntest);
$html .= $filter->doFilter($markdowntest, 'markdown');

$filtertest = <<<EOD
## Filtrera markdown och url
En lista med länkar:

+ Utan url
+ https://github.com/
+ http://php.net/manual/en/langref.php
EOD;
$html .= $filter->doFilter($filtertest, 'markdown, clickable');

// Do it and store it all in variables in the loom container.
$loom['title'] = "Testa CTextFilter";
$loom['main'] = $html;



// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
