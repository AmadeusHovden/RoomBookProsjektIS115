<h1>Tilgjengelige rom for dine valg</h1>

<section class="hero">
<?php
// Inkluderer databasen
require_once '../privat/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hent innsjekk- og utsjekksdato samt antall gjester fra POST-forespørselen
    $dateFrom = $_POST['date-from'];
    $dateTo = $_POST['date-to'];
    $guests = $_POST['guests'];

    // Valider datoene
    $dateFromObj = new DateTime($dateFrom);
    $dateToObj = new DateTime($dateTo);
    $today = new DateTime();

    // Sjekk at datoene er gyldige
    if ($dateFromObj < $today->setTime(0, 0, 0)) {
        echo "Dato fra kan ikke være tidligere enn dagens dato.<br>";
    } elseif ($dateToObj <= $dateFromObj) {
        echo "Dato til må være senere enn dato fra.<br>";
    } else {
        // SQL-spørring for å finne rom som ikke er booket i den valgte perioden
        $sql = "SELECT * FROM rooms WHERE id NOT IN (
                    SELECT room_id FROM bookings 
                    WHERE (check_in < :dateTo AND check_out > :dateFrom)
                ) AND max_adults >= :guests AND availability = TRUE";

        // Forbered SQL-spørringen
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dateFrom', $dateFrom);
        $stmt->bindParam(':dateTo', $dateTo);
        $stmt->bindParam(':guests', $guests);
        $stmt->execute();

        // Hent resultatene fra spørringen
        $availableRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vis de tilgjengelige rommene
        if (count($availableRooms) > 0) {
            foreach ($availableRooms as $room) {
                echo "Romtype: " . $room['room_type'] . " - Pris per natt: " . $room['price_per_night'] . " kr<br>";
            }
        } else {
            echo "Ingen rom tilgjengelige for de valgte datoene.";
        }
    }
} else {
    echo "Ingen data mottatt.";
}
?>
</section>
