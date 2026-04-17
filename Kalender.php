<?php
// --- Konfiguration & Navigation ---
$jahr = isset($_GET['y']) ? intval($_GET['y']) : date('Y');
$monat = isset($_GET['m']) ? intval($_GET['m']) : date('n');

// Korrektur für Monatswechsel (Januar zurück / Dezember vor)
if ($monat < 1) { $monat = 12; $jahr--; }
if ($monat > 12) { $monat = 1; $jahr++; }

$ersterTag = mktime(0, 0, 0, $monat, 1, $jahr);
$tageImMonat = date('t', $ersterTag);
$wochentagStart = date('N', $ersterTag);

// Links für Navigation
$prevM = $monat - 1; $prevY = $jahr;
$nextM = $monat + 1; $nextY = $jahr;

// --- Feiertage & Ferien (API-Abfrage) ---
// Hier nutzen wir beispielhaft eine Funktion für Feiertage; 
// Für echte Ferien empfiehlt sich die Einbindung von schulferien-api.de
function getFeiertage($year) {
    $easter = easter_date($year);
    return [
        date('Y-m-d', mktime(0,0,0,1,1,$year))   => 'Neujahr',
        date('Y-m-d', mktime(0,0,0,1,6,$year))   => 'Heilige Drei Könige',
        date('Y-m-d', $easter - 86400 * 2)        => 'Karfreitag',
        date('Y-m-d', $easter + 86400)            => 'Ostermontag',
        date('Y-m-d', mktime(0,0,0,5,1,$year))   => 'Tag der Arbeit',
        date('Y-m-d', $easter + 86400 * 39)       => 'Christi Himmelfahrt',
        date('Y-m-d', $easter + 86400 * 50)       => 'Pfingstmontag',
        date('Y-m-d', $easter + 86400 * 60)       => 'Fronleichnam',
        date('Y-m-d', mktime(0,0,0,8,15,$year))  => 'Mariä Himmelfahrt',
        date('Y-m-d', mktime(0,0,0,10,3,$year))  => 'Tag der Dt. Einheit',
        date('Y-m-d', mktime(0,0,0,11,1,$year))  => 'Allerheiligen',
        date('Y-m-d', mktime(0,0,0,12,25,$year)) => '1. Weihnachtstag',
        date('Y-m-d', mktime(0,0,0,12,26,$year)) => '2. Weihnachtstag',
    ];
}
$feiertage = getFeiertage($jahr);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bayern Kalender - <?php echo date('F Y', $ersterTag); ?></title>
    <style>
        .calendar-container { max-width: 600px; margin: auto; font-family: Arial; }
        .nav { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .calendar { border-collapse: collapse; width: 100%; }
        .calendar th, .calendar td { border: 1px solid #ccc; padding: 10px; text-align: center; width: 14%; }
        .weekend { background-color: #f9f9f9; color: #666; }
        .holiday { background-color: #ffcccc; }
        .today { font-weight: bold; outline: 2px solid blue; }
        .btn { padding: 5px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>

<div class="calendar-container">
    <div class="nav">
        <a class="btn" href="?m=<?php echo $prevM; ?>&y=<?php echo $prevY; ?>">&laquo; Zurück</a>
        <strong><?php echo date('F Y', $ersterTag); ?></strong>
        <a class="btn" href="?m=<?php echo $nextM; ?>&y=<?php echo $nextY; ?>">Vor &raquo;</a>
    </div>

    <table class="calendar">
        <tr><th>Mo</th><th>Di</th><th>Mi</th><th>Do</th><th>Fr</th><th>Sa</th><th>So</th></tr>
        <tr>
            <?php
            // Leere Zellen am Anfang
            for ($i = 1; $i < $wochentagStart; $i++) echo "<td></td>";

            for ($tag = 1; $tag <= $tageImMonat; $tag++) {
                $datum = date('Y-m-d', mktime(0, 0, 0, $monat, $tag, $jahr));
                $wochentag = date('N', strtotime($datum));
                
                $klassen = [];
                if ($wochentag >= 6) $klassen[] = 'weekend';
                if (isset($feiertage[$datum])) $klassen[] = 'holiday';
                if ($datum == date('Y-m-d')) $klassen[] = 'today';

                echo "<td class='" . implode(' ', $klassen) . "' title='" . ($feiertage[$datum] ?? '') . "'>$tag</td>";

                if ($wochentag == 7 && $tag < $tageImMonat) echo "</tr><tr>";
            }
            ?>
        </tr>
    </table>
</div>

</body>
</html>