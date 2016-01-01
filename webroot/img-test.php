<?php
/**
 * This is a loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');
$loom['stylesheets'][] = 'css/figure.css';
$loom['stylesheets'][] = 'css/gallery.css';
$loom['stylesheets'][] = 'css/breadcrumb.css';

$loom['title'] = "Img testsida";
$loom['main'] = <<<EOD
<h1>{$loom['title']}</h1>
<p>En testsida för att pröva ut <a href="source.php?path=webroot/img.php">img.php</a> och <a href="source.php?path=src/CImage/CImage.php">CImage.php</a>.</p>
<div style="display: inline-block">
<img src='img.php?src=checked_pattern.jpg&width=228&height=128&crop-to-fit&no-cache' alt=''/>
<img src='img.php?src=me.jpg&width=228&height=128&crop-to-fit' alt=''/>
<img src='img.php?src=me.jpg&width=228&height=128&' alt=''/>
<img src='img.php?src=me.jpg&width=228&height=128&no-cache&sharpen' alt=''/>
<img style="background-color: pink;" src='img.php?src=Loom-icon-transp.png&width=200' alt=''/>
<img src='img.php?src=me.jpg&width=100&height=200&no-cache' alt=''/>
<img src='img.php?src=movie/issue40/source.jpg&width=400&height=200' alt=''/>
<img src='img.php?src=checked_pattern.jpg&width=228&height=128&crop-to-fit' alt=''/>
</div>
EOD;

// Finally, leave it all to the rendering phase of loom.
include(LOOM_THEME_PATH);
