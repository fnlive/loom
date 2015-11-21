<?php
/**
 * This is a Loom pagecontroller.
 *
 */
// Include the essential config-file which also creates the $loom variable with its defaults.
include(__DIR__.'/config.php');


// Do it and store it all in variables in the Loom container.
$loom['title'] = "Redovisningar";

$loom['main'] = <<<EOD
<h1>Redovisningar</h1>
<h2>kmom01</h2>
<!-- Vilken utvecklingsmiljö använder du? -->
<p>Min utvecklingsmiljö består av XAMPP, Firefox med FireBug, cygwin kommandotolk och Atom texteditor. Jag kör på olika Windows 7 datorer och har kursrepot lagrat på Dropbox för att kunna växla smidigt mellan datorerna. Kör även Chrome och Internet Explorer 9 för extra test. Jag ska även börja använda git och github för revisionshantering av kursprojekten framöver.
</p>
<!-- Berätta hur det gick att jobba igenom guiden “20 steg för att komma igång PHP”, var något nytt eller kan du det? -->
<p>Jag kände igen det mesta från föregående kurs, htmlphp. Lite repetition samt plockade upp några nya bitar.
</p>
<!-- Vad döpte du din webbmall Anax till? -->
<p>Jag döpte min webbmall till "Loom". Loom är det engelska ordet för vävstol som används för att väva olika textila strukturer. Med Loom kan man skapa flexibla webbplatser med en bra struktur.
</p>
<!-- Vad anser du om strukturen i Anax, gjorde du några egna förbättringar eller något du hoppade över? -->
<p>Till att börja med kändes det som att det var många foldrar och filer i Anax struktur, och lite oklart var olika saker borde placeras. Det känns just nu inte självklart vad som bör läggas under /src, /theme eller respektiv sida. Gränsdragning mellan innehåll, funktion och utseende känns inte alltid tydligt. Jag har flyttat runt funktioner en hel del under arbetets gång. Tänker att detta klarnar mer framöver.
</p>
<!-- Gick det bra att inkludera source.php? Gjorde du det som en modul i ditt Anax? -->
<p>Det gick relativt smidigt att inkludera source.php. Jag lade in den som en modul i "Loom". Jag började med att använda @import i stylesheet source.css för att få med style.css. Kändes klumpigt så jag läste på lite mer och hittade ett bättre sätt.
</p>
<!-- Gjorde du extrauppgiften med GitHub? -->
<p>
</p>
<p>
</p>

<h2>kmom02</h2>
<p>
</p>
<h2>kmom03</h2>
<p>
</p>
<h2>kmom04</h2>
<p>
</p>
<h2>kmom05</h2>
<p>
</p>
EOD;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
