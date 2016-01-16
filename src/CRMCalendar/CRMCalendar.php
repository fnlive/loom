<?php
/**
 * Display movie calendar
*/
class CRMCalendar
{

    public static function output($db, $displayDate=null)
    {
        setlocale(LC_TIME, "Swedish");
        define("CHARSET", "iso-8859-1");

        if (null == $displayDate) {
            $date = new DateTime();
        } else {
            $date = new DateTime($displayDate);
        }
        $today = new DateTime();
        // Remove time, only keep date when we compare later.
        $today->setTime(0, 0);
        // $date = new DateTime('2016-03-18');  // For testing only

        // Find first week and day in month and first day in that week
        $year = $date->format('Y');
        $month = $date->format('m');
        $firstDayInMonth = new DateTime();
        $firstDayInMonth->setDate($year, $month, 1);
        $dayOfWeek = $firstDayInMonth->format('N');
        $subtractDays = $dayOfWeek - 1;
        $date = new DateTime($firstDayInMonth->format('Y-m-d'));
        $date->modify("-{$subtractDays} day");

        // Find last week and day in month and last day in that week
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $lastDayInMonth = new DateTime("$year-$month-$daysInMonth");
        $dayOfWeek = $lastDayInMonth->format('N');
        $addDays = 7 - $dayOfWeek;
        $lastDayInLastWeek = new DateTime($lastDayInMonth->format('Y-m-d'));
        $lastDayInLastWeek->modify("+$addDays day");

        // Navigation links to previous and next month. And heading
        $prevMonth = new DateTime($firstDayInMonth->format('Y-m-d'));
        $thisMonthText = utf8_encode(strftime("%B %Y", strtotime( $prevMonth->format('Y-m-d'))));
        $prevMonth->modify('-1 month');
        $prevMonthText = utf8_encode(strftime("%B", strtotime( $prevMonth->format('Y-m-d'))));
        $nextMonth = new DateTime($firstDayInMonth->format('Y-m-d'));
        $nextMonth->modify('+1 month');
        $nextMonthText = utf8_encode(strftime("%B", strtotime( $nextMonth->format('Y-m-d'))));
        $calenderNavigation = "
        <div class=\"prev-month-nav\"><a href=\"?date={$prevMonth->format('Y-m-d')}\">&laquo;$prevMonthText</a></div>
        <div class=\"next-month-nav\"><a href=\"?date={$nextMonth->format('Y-m-d')}\">$nextMonthText&raquo;</a></div>
        <div class=\"month-year\">$thisMonthText</div>
        ";

        // Create calendar table
        $table = "<div class='calendar-table'><table>
            <tr>
                <th></th><th>Måndag</th><th>Tisdag</th><th>Onsdag</th><th>Torsdag</th><th>Fredag</th><th>Lördag</th><th>Söndag</th>
            </tr>";
        while ($date <= $lastDayInLastWeek) {
            $table .= "<tr>";
            // $w = strftime("%W", strtotime($date->format('Y-m-d'))); // Non-iso weeks. Don't use.
            $w = ltrim($date->format('W'), 0);
            $table .= "<td class='week'>Vecka<br>$w</td>";
            for ($d=1; $d < 8; $d++) {
                // $dateText = utf8_encode(strftime("%A %e %B", strtotime($date->format('Y-m-d'))));
                // %e does not work on windows, use %d instead
                $dateText = utf8_encode(strftime("%A %#d %B", strtotime($date->format('Y-m-d'))));
                // $dateText = $date->format('D d M');
                $redDay = (7==$d) ? "red-day" : "";
                $classToday = ($date == $today) ? "today" : "";
                $classThisMonth = ($date < $firstDayInMonth || $date > $lastDayInMonth) ? "class-outside-month" : "class-inside-month";
                $table .= "<td class='day $redDay $classToday $classThisMonth' >$dateText</td>";
                $date->modify("+1 day");
            }
            $table .= "</tr>\n";
        }
        $table .= "</table></div>";

        // Create html for movie of the month-year
        $out = "<div style=\"float: left; margin: 20px;\">";
        // $out .= "<h2>Mest populära film</h2>";
        $movies = new CRMMovie($db, $month);
        $out .= $movies->outputMovieCard();
        $out .= "</div>";
        $out .= "<div class=\"clear-both\"></div>";


        return $out . "<div class='calendar'>" . $calenderNavigation . $table . "</div>";
    }
}
