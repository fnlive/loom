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
<p>Efter feedback har jag flyttat ut i princip all kod från controller-sidan till C100Game-klassen. Det fungarade ganska smärtfritt. Det blev en del små funktioner som bara används lokalt inuti C100Game, men det känns OK. Det var lite krångligt med hur jag skulle återställa C100Game-objektet från session-variabeln. Efter lite klurande läste jag in objektet i ett temporärt C100Game-objekt och kopierade över lämpliga egenskaper i konstruktorn.
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
<!-- Hur kändes det att jobba med PHP PDO? -->
<p>Jag tycker det fungerar smidigt att arbeta med PHP PDO. Det som känns svårast är att förstå och skriva rätt SQL-syntax med queries som är lite mer komplexa. Speciellt när flera tabeller länkas ihop med join samt vid användning av skapade vyer.
</p>
<!-- Gjorde du guiden med filmdatabasen, hur gick det? -->
<p>Jag arbetade igenom guiden med filmdatabasen genom att läsa guiden och göra delar av övningarna. Jag använde sedan guiden som referens när jag löste uppgifterna.
<p>Vid lösning av uppgifterna blev det ganska mycket copy/paste/refactoring av kod in i Anax-ramverket. Uppgifterna var nyttiga gennom att utöver förståelsen för databashantering med php och sql, så gav det övning på att läsa och felsöka i annans kod vid integrering av koden i ramverket. Funktionen dump() fick jag bra användning för.
</p>
<!-- Du har nu byggt ut ditt Anax med ett par moduler i form av klasser, hur tycker du det konceptet fungerar så här långt, fördelar, nackdelar? -->
<p>Konceptet med Anax ramverk börjar kännas bra. Det börjar kännas lättare att navigera runt i källkoden och förstå var olika saker finns definierade. Ramverket uppmuntrar till att bryta upp funktionalitet i mindre beständsdelar i form av klass-metoder och controller-sidor. Det är fortfarande klart för mig hur mycket kod som bör läggas in i klasser (dvs. kod/klass som bör vara återanvändbar direkt eller vid senare tillfälle) respektive hur mycket kod som bör gå in i controller-sidan. Detta kanske kommer med erfarenhet samt växer fram allt eftersom remverket växer fram och används mer. Allt eftersom nya krav tillkommer dyker det upp behov på refaktorering av klasser och controller-sidor.
</p>
<p>Jag gav mig också på uppgiften med dynamisk navbar. Kan erkänna att jag inte riktigt trängt igenom kodkonstruktionerna med anonyma funktioner, funktionspekare och rekursion. Detta behöver lite mer tid för att sjunka in. Större delen av tiden ägnade jag åt att få till liknande style på navbaren som jag hade innan. Detta tog en del omskrivning av css-regler och felsökning med firebug som jag lärde mig en hel del på.
</p>
<h2>kmom05</h2>
<!-- Det blir en del moduler till ditt Anax nu, hur känns det? -->
<p>Den här uppgiften med att bygga ett CMS har varit väldigt nyttig. Jag har försökt börja med att programmera utan att snegla för mycket på övningarna. När jag gjort färdigt ett steg eller fastnat på något har jag gått tillbaks och läst igenom övningarna för att fånga upp det jag eventuellt missat. Jag är inte helt säker på att jag konstruerar modulerna på ett bra objektorienterat vis. Det känns som vissa moduler blir mer av funktionsbibliotek än objekt. Kanske är det mer träning och erfarenhet för att få det att kännas bätre.
</p>
<!-- Berätta hur du tänkte när du löste uppgifterna, hur tänkte du när du strukturerade klasserna och sidkontrollerna? -->
<p>När jag strukturerade koden försökte jag förstå vilka medlemsvariabler som borde finnas i modulerna. För CContent kändes det enbart vettigt att ha en instans av databasobjektet lagrat. För de övriga modulerna blev det inga medlemsvariabler så metoderna i CBlog och CPage fick bli statiska. När jag började lägga ut koden så hamnade den ganska likafördelat i controller-sidorna och modulerna. Efter att jag fått upp rätt funktionalitet gick jag vidare och refaktorerade genom att flytta ut så mycket kod som möjligt till modulerna för att sedan samla ihop likartade kodblock, t.ex. sanering av data. Jag valde att ha 4 olika controller-sidor för administration av CMS'et, view, create, edit och delete.php. Halvvägs igenom tänkte jag att de borde slås ihop till en controller-sida byggd som en multi-sida styrd med en \$_GET variabel. Baksidan hade dock blivit en betydligt mer komplex controller-sida, så jag valde att behålla det första upplägget.
</p>
<!-- Börjar du få en känsla för hur du kan strukturera din kod i klasser och moduler, eller kanske inte? -->
<p>Huruvida jag förstår hur jag kan strukturera kod i klasser och moduler så är nog svaret att jag inte vet. Eller jag förstår nog bättre nu att det är svårt och att det behövs mer erfarenhet. Kanske en bra start.
</p>
<!-- Snart har du grunderna klara i ditt Anax, grunderna som kan skapa många webbplatser, är det något du saknar så här långt, kanske några moduler som du känner som viktiga i ditt Anax? -->
<p>Just nu känner jag inte att det saknas något speciellt i mitt Anax, eller Loom. Ramverket känns väldigt enkelt och avskalat, men flexibelt och möjliggör att skapa väldigt många olika slags webb-applikationer, genom att lägga till controller-sidor och moduler. Det kändes kul att på relativt kort tid kunna skapa ett enkelt CMS med ramverket.
</p>
<p>Jag gjorde också extra-uppgifterna slugify, koppla användare till CMS, samt visa vem som författat ett blogg-inlägg. För att kunna skapa eller redigera innehåll i CMS så måste man vara inloggad.
</p>
<h2>kmom06</h2>
<!-- Hade du erfarenheter av bildhantering sedan tidigare? -->
<p>Tidigare erfarenhet av bildhantering har varit begränsad till redigering med photoshop, irfanview och gimp. Jag har aldrig gjort bildhantering med något programmeringsspråk. Denna uppgift öppnade upp ögonen för ett alternativt sätt att skapa och skicka bilder med rätt storlek från en webbplats. Först verkade det väldigt omständligt och processorkrävande att skapa bilder "on-the-fly". Men med egen cache-hantering blir overhead låg efter att första bilden skapats. I t.ex. WordPress skapas många bilder med olika storlek som lagras på servern oavsett om de används eller ej.
</p>
<!-- Hur känns det att jobba i PHP GD? -->
<p>Det kändes ganska rättframt att jobba med just själva PHP GD. Det var desto mer att hålla reda på vad gällde t.ex. förändring av storlekar på bilder med gammal/ny höjd/bredd, "aspect ratio", crop-storlekar etc.
</p>
<!-- Hur känns det att jobba med img.php, ett bra verktyg i din verktygslåda? -->
<p>Sidan img.php kändes näst intill omöjlig att få ett helhetsgrepp på. Det var ganska krävande att skapa en CImage klass utifrån img.php. När klassen var färdig kändes det som jag fick grepp på det. Egentligen ett ganska rättframt flöde med validering av in-parametrar och kontroll om bild finns i cachen och då serva den; Om ej i cachen, processa fram bilden och lagra den som fil i cachen. Kan absoulut vara vara nyttigt att dynamiskt kunna processa fram en bild med rätt storlekt, format etc.
</p>
<!-- Detta var sista kursmomentet innan projektet, hur ser du på ditt Anax nu, en summering så här långt? -->
<p>Under 2 månaders tid har det gått att utveckla flera olika grundläggande webb-funktioner såsom ett CMS-system med "sidor och blogg", film-databas med sökfunktion, och foto-galleri. Tycker jag fått fram mycket funktionalitet under relativt kort tid. De här funktionerna går att vidareutveckla till nya fuktioner såsom t.ex. ett forum med diskussions-trådar, webb-shop, etc. De kan dessutom samexistera med de andra funktionerna i ramverket med funktionerna isolerade från varandra på ett strukurerat sätt. Så långt tycker jag att jag lärt mig väldigt mycket i dessa kursmoment.
</p>
<!-- Finns det något du saknar så här långt, kanske några moduler som du känner som viktiga i ditt Anax? -->
<p>Det finns många funktioner som man skulle kunna bygga ut mitt Loom/Anax med. En webbshop skulle vara nästa naturliga modul att utveckla. En kombination av funktionerna i filmdatabasen och bloggen med lite nya funktioner som kundvagn skulle kunna bli en bra start.
</p>
<p>Om man skulle fortsätta utveckla detta ramverk för användning i olika webb-produkter, behövs det något slags automatiserat enhets-test ramverk. Just nu är det ganska tidskrävande att testa igenom att alla funktioner fortfarande fungerar även om man bara ändrar en mindre detalj i någon modul.
</p>
<p>Jag har också konsekvent glömt att uppdatera min sitemap.xml. Denna skulle jag vilja hålla uppdaterad på något automatiskt eller halvautomatiskt sätt via en admin-controller-sida. Finns nog fler saker man vill kunna uppdatera via webb-gränssnitt, t.ex. ladda upp ny favicon, ladda upp bilder, etc. En annan funktion jag gärna skulle vilja ha är en rättstavningsmodul som blog/page-modulerna kan använda sig av. Balansakt mellan hur komplex php-kod för admin-saker man får jämfört med värdet av användarvänlighet.
</p>
<p>Finns givetvis många fler funktioner. En modul för att skicka nyhetsbrev till prenumeranter samt registrering och lagring av prenumeranterna. Till en blogg kan man också behöva en modul som skapar ett rss-flöde. Modul för kommentars-system till bloggen. Modul för forum där besökare kan skapa diskussionstrådar inom olika ämnen. etc. etc.
</p>
<p>Jag lade också till stöd för transparenta png-bilder. En lätt uppdatering med hjälp av artikeln. Den transparenta loggan för Loom fick en rosa bakgrund på <a href="img-test.php">"img-testsida"</a>. Fick däremot inte filnamn med åäa att fungera då realpath inte verkar gilla dessa. Hann inte gräva vidare i detta.
</p>
<h2>kmom07/10</h2>
<!-- 1. På din redovisningssida, skriv följande: -->

<!-- 1.1. Länka till din projektsida för RM. -->
<p>Länk till RM ...
</p>

<!-- 1.2. För varje krav du implementerat, dvs 1-6, skriver du ett textstycke om ca 5-10 meningar där du beskriver vad du gjort och hur du tänkt. Poängsättningen tar sin start i din text så se till att skriva väl för att undvika poängavdrag. Missar du att skriva/dokumentera din lösning så blir det 0 poäng. Du kan inte komplettera en inlämning. -->
<!-- Krav 1: Struktur och innehåll -->
<h3></h3>
<p>Detta krav implementerade jag i två steg. I det första steget utgick jag från mitt Loom-ramverk från kmom06 och gjorde jag en vädligt enkel logga med huvudtitel och slogan samt en enkel footer. För att kuknna göra resten behövde jag först göra färdigt Film-sidan och cms-delen för sidor och blog-posts som även ingick i krav 2 3, och 4. Efter att dessa var klara var det enkelt uppdatera menyn för att visa rätt sidor.
</p>
<p>Menyn är uppbyggd dynamiskt och visar olika val beroende på om användaren är autentiserad eller ej. Om användaren är autentiserad så dyker extra sub-menuval upp under "Filmer" och "Nyheter". Det finns också ett användare-menyval som ändrar mellan "Login" och "Logout" beroende på om användaren är autentiserad eller ej. Meny-funktionen är väldigt smidig att arbeta med och det är enkelt att dynamiskt lägga till eller dra ifrån meny-val.
</p>
<p>Sidan om företaget valde jag att göra med hjälp av page-funktionen i Loom CMS och är skriven i Markdown.
</p>
<p>Första sidan skapas av en page-controller och strukturen går därmed inte att ändra på. Däremot ändras senaste filmer och blogg-inlägg när dessa uppdateras.
</p>

<!-- Krav 2: Sida - Filmer -->
<h3>Krav 2: Sida - Filmer</h3>
<p>Jag utgick från film-funktionen från kmom04. Filmerna lagras i en ny tabell för att inte påverka kmom04. Jag har lagt till funktion för att skapa ny film, uppdatera en film, samt återställa filmdatabasen med default-innehåll. Jag använder img.php för att serva nedskalade bilder, s.k. thumbnails, på film-posters. Det finns också tillagt till uppdateringsdatum för varje film som ny kolumn. Genom detta går det då att söka fram ett varierbart antal filmer sorterade med uppdateringsdatum. Detta används på första sidan för att kunna visa de tre senaste filmerna. Tabellen innehåller också två kolumner med id-nummer för filmen kopplat till motsvarande film på imdb samt trailer på youtube. Med id't går det att skapa url till imdb och youtube.
</p>
<p>För att administrera filmer (skapa, film, uppdatera, återställa filmdatabas) skapade jag en ny klass CCRMMovieAdmin. Klassen används från page-controllern rm-movieadmin.php. Klassen används även för att generera html hyper-länkar för att redigera eller radera en film.
</p>
<p>Uppdatering av filmgenre krävde lite efterforskning. Jag försökte först få till en sql-query som kunde både uppdatera både film- och film-till-genre-tabellen samtidigt. Detta verkade dock inte möjligt att åstadkomma när det fanns en-till-många relationer mellan tabellerna. Istället måste film-till-genre-mappningen för en film raderas först, för att sedan lägga till ny uppdaterad mappning i ett andra steg.
</p>
<p>Filmkort ... Nya klasser??
</p>
<!-- Krav 3: Sida - Nyheter -->
<h3></h3>
<p>Ny tabell, kategori på varje blogg-inlägg, extract, num of posts, TrimText to make extract,  ...
Här utgick jag också från blogg-delen från övning kmom05. Den stora utökningen var att lägga till kategori på blogg-inläggen. Då det bara behövdes en kategori per inlägg var det relativt lätt att lägga till. För filmgenrer blir det mer komplext med en-till-många mappning mellan film-genrer.
</p>
<!-- Krav 4: Första sidan -->
<h3>Krav 4: Första sidan</h3>
<p>Första sidan fick bli en ny page controller. En tanke var att använda page.php och lagra sid-innehåll i databasen Detta hade då krävt stöd av någon slags kortkommandon som identifieras i textfilter-funktionen och då kan köra någon utpekad php-funktion som genererar html. Istället fick det bli php rätt upp och ner i controllern.
</p>
<p>För vart och ett av kraven i första sidan har det krävts mindre utökningar och ändringar av t.ex. CBlog och film-db-klasserna.
</p>
<p>
</p>
<!-- Krav 5, 6: Extra funktioner (optionell) -->
<h3></h3>
<p>
</p>
<p>
</p>

<!-- 1.3. Skriv ett allmänt stycke om hur projektet gick att genomföra. Problem/lösningar/strul/enkelt/svårt/snabbt/lång tid, etc. Var projektet lätt eller svårt? Tog det lång tid? Vad var svårt och vad gick lätt? Var det ett bra och rimligt projekt för denna kursen? -->
<h3>Allmänt om hur projektet gick</h3>
<p>För alla krav och och moment i projektet har det gått att utgå ifrån vad som byggts upp under inlämningsuppgifterna. Varje krav har i regel krävt något tilläg eller modifiering av befintlig kodbas, men de har varit relativt rakt på sak. Mina klasser för sökning och visning av filmer har ställt till mest problem vid utökning och modifiering av funktion. De varit lite dåligt organiserade med otydlig uppdelning av vad varje klass skall kunna. Jag har lagt in för mycket funktion i vissa klasser, t.ex. klassen CRMMovieSearch borde kanske delas upp i några mindre och mer väl sammanhållna klasser. Här hade jag velat lägga mer tid på att omstrukturera koden.
</p>
<p>Att få till bra och fungerande sql-satser har tagit en del tid. I regel har jag använt "MySQL Workbench" först för att testa ut satserna för att sedan lägga in dem i php-koden. Det är smidigt att ha "MySQL Workbench" uppe parallellt med att man kodar och testar uppdatering av databas för att snabbt se vad som lagrades i databasen. Med sql-script på fil är det väldigt lätt att ha sparade sql-satser för att se innehåll eller droppa tabeller, etc, utöver att testa ut nya komplexa sql-satser.
</p>
<p>För mig har det varit ett rimligt projekt som varit lagom utmanande.
</p>

<!-- 1.4. Avsluta med ett sista stycke med dina tankar om kursen och vad du anser om materialet och handledningen (ca 5-10 meningar). Ge feedback till lärarna och förslå eventuella förbättringsförslag till kommande kurstillfällen. Är du nöjd/missnöjd? Kommer du att rekommendera kursen till dina vänner/kollegor? På en skala 1-10, vilket betyg ger du kursen? -->
<h3>Tankar om kursen</h3>
<p>Denna kursen har utvecklat mina php och sql färdigheter väldigt mycket. Jag har också stärkt färdigheterna i debugging och i versionshantering med git. Kursupplägget med mycket inlämningsuppgifter har hjälpt till stort. Det jag tycker saknas något är teori och övning på bra objektorienterad kod. Vilket tänk skall man ha när man väljer vad en klass skall ha för  ansvar och hur stor det skall vara. Jag kan dock se att t.ex. mina klasser för filmhantering borde ha lagts upp annorlunda och att de inte höll så bra för att byggas vidare på utifrån den nya kraven i projektet. Men det kanske behöver komma i ett nästa steg. Överlag är jag väldigt nöjd med denna kurs och skulle vilja ge den en 9'a på en skala 1-10.
</p>

<!-- 2. Ta en kopia av texten på din redovisningssida och kopiera in den på Its/redovisningen. Glöm inte länka till din me-sida och projektet. -->

<!-- 3. Ta en kopia av texten från din redovisningssida och gör ett inlägg i kursforumet och berätta att du är klar. -->
EOD;

// Finally, leave it all to the rendering phase of Loom.
include(LOOM_THEME_PATH);
