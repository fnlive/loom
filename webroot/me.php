<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');


// Do it and store it all in variables in the Loom container.
$loom['title'] = "About";

$loom['main'] = <<<EOD
<h1>Om mig</h1>
<figure class="right" >
  <img src="img/malte-husse-kastar-pinne-w330.jpg" class="image-300" alt="Fredrik kastar pinne med Malte.">
  <figcaption class="caption">Fredrik kastar pinne med Malte.</figcaption>
</figure>
<p>Jag heter Fredrik Nilsson och bor i Pixbo strax sydost om Göteborg. Där bor jag med min hustru Ulrika, två döttrar Karin och Anna, och dvärgschnauzern Malte.
</p>
<p>Jag läser kursen "Databaser och objektorienterad programmering i PHP" på Blekinge Tekniska Högskola. Min målsättning är att lära mig html, css, php och javascript, och med denna grund kunna skapa bra webbplatser.
</p>
<p>Jag driver för närvarande en webbplats för den släktförening som jag är medlem i. Webbplatsen är byggd med WordPress. Det har behövts olika mindre anpassningar för att få rätt utseende och funktion. Med bättre grunder inom webbutveckling kan jag ta det till en nivå till. Släktföreningens webbplats kombinerar flera intressen jag har: historia, släktforskning, programmering och datorer.
</p>
<figure class="left">
  <img src="img/knäbäckshusen-w330.jpg" class="image-300" alt="Med familjen på vandring. Hustru Ulrika bakom kameran.">
  <figcaption class="caption image-300">Med familjen på vandring. Hustru Ulrika bakom kameran.</figcaption>
</figure>
<p>När jag inte är på jobbet eller sitter bakom datorn hemma är jag gärna ute i skogen eller vid havet på vandring. I somras lyckades jag med bedriften att bestiga Slåttdalsberget på Höga Kusten.
</p>
EOD;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
