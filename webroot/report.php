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
<p>Det mesta i guiden “20 steg för att komma igång PHP” kändes igen från föregående kurs, htmlphp. Lite repetition samt plockade upp några nya bitar.
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
<p>Jag har lagt upp en första version av "Loom" på <a href="https://github.com/fnlive/loom">github</a>. Jag fick inte ordning "git push" från bash/cygwin utan gjorde detta från Windows power shell (posh) som jag hade fungerande sedan tidigare. Skall göra försök att få igång det även från cygwin.
</p>

<h2>kmom02</h2>
<!-- Hur väl känner du till objektorienterade koncept och programmeringssätt? -->
<p>Jag känner till OO koncept och programmeringssätt hyggligt väl, men har inte någon större erfarenhet av användning av det i arbetslivet. Jag har gjort några mindre projekt i C++ och java för många år sedan, så lite av det tankesättet finns kvar.
</p>
<!-- Jobbade du igenom oophp20-guiden eller skumläste du den? -->
<p>Jag läste igenom oophp20-guiden först men gjorde inte övningarna själv. Gick på strategin att gå direkt på uppgiften och sedan gå tillbaks till guiden för att plocka upp bra saker. Jag tyckte annars guiden gav ett bra koncentrat av OO i PHP.
</p>
<!-- Berätta om hur du löste uppgiften med tärningsspelet 100, hur tänkte du och hur gjorde du, hur organiserade du din kod? -->
<p>För att lösa uppgiften med tärningsspelet började jag med att skissa ner någon slags textuell kombinerad variant av Class diagram, Interaction diagram, och <a href="http://www.extremeprogramming.org/rules/crccards.html">CRC-kort</a>. Jag utgick från förslaget i uppgiften med de tre klasserna CDice, C100Round, C100Game. Sedan satte jag igång att koda.
</p>
<p>Jag fick igång en fungerande prototyp med enkel rudimentär utskrift och spelkontroll. Insåg då att väldigt mycket kod hade hamnat i en metod i C100Game och nästan ingen kod i controller-sidan dice-100.php. Jag ville då renodla CGame och och bara ha kvar spel-logiken där och flytta ut "presentationen" av spelet till controller-sidan. CDice-klassen är väldigt enkel och har inget minne. Senaste slaget lagras istället i klassen C100Game. C100Round kommer ihåg antalet poäng i rundan. Klassen kollar också av om man slår en etta och då nollställer ackumelerade slag i omgången. C100Game ackumulerar säkrade poäng från avslutade omgångar och kollar om spelaren har vunnit.
</p>
<p>Jag blev ganska nöjd med den nya fördelningen. Man hade kunnat flytta tillbaks generering av html för "spelkontroll" och "poängställning" från controller-sida till klassen C100Game till en ny metod. Eventuell framtida ändring av "speltavlan" kanske man då kan lösa genom arv och överlagring med ny klass-metod.
</p>
<p>Sist skapade jag en unik css-fil för tärningsspelet för att få rätt utseende på speltavlan.
</p>
<p>Efter feedback har jag flyttat ut i princip all kod fron controller-sidan till C100Game-klassen. Det fungarade ganska smärtfritt. Det blev en del små funktioner som bara används lokalt inuti C100Game, men det känns OK. Det var lite krångligt med hur jag skulle återställa C100Game-objektet från session-variabeln. Efter lite klurande läste jag in objektet i ett temporärt C100Game-objekt och kopierade över lämpliga egenskaper i konstruktorn.
</p>

<h2>kmom03</h2>
<!-- Är du bekant med databaser sedan tidigare? Vilka? -->
<p>Jag har använt SQLite i kursen htmlphp. Har även ytligt kommit i kontakt med MySQL som används av WordPress. Men har bara gjort lite mindre handgrepp via phpMyAdmin, såsom exportera/importera databas samt sätta password.
</p>
<!-- Hur känns det att jobba med MySQL och dess olika klienter, utvecklingsmiljö och BTH driftsmiljö? -->
<p>Det fungerar hyggligt bra att jobba med alla olika klienter. Vid SQL-övningarna jobbade jag bara i min lokala miljö med XAMP. Allra mest med MySQL Workbench som jag tyckte var smidig. Enkelt att klippa, klistra och editera i querys samt att köra enstaka rader eller flera. Jag provade också mySQL i BTH-miljön. Här testade jag phpMyAdmin och MySQL CLU. CLU körde jag från cygwin med ssh. phpMyAdmin kändes smidigast då det är lättare att klippa/klistra samt exekvera querys.
</p>
<!-- Hur gick SQL-övningen, något som var lite svårare i övningen, kändes den lagom? -->
<p>SQL-övningen kändes som en lång maraton-djupdykning i SQL. Det var skönt att komma upp till ytan igen efter övningen. SQL är ett större och mer komplext språk än vad jag trott tidigare. Övningarna gick rätt bra. Lite knepigt när man fastnade på något syntax-fel där felutskrift var svår att tolka. Övningarna mot slutet med inner/outer join kändes rätt kluriga. Här behövs mer för att få det att sitta. Men det känns som om jag kommit in i SQL's grunder ganska bra genom övningen. 
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
