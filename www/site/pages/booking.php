


<section class="hero">
    <h3>Kommer videre booking</h3>
    <br>

 <?php
// Inkluderer filen som oppretter en forbindelse til databasen
require_once '../privat/db.php'; 

// SQL-spørring for å hente alle rom fra 'rooms'-tabellen
$sql = "SELECT * FROM rooms";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rooms as $room) {
    echo "Romtype: " . $room['room_type'] . " - Pris: " . $room['price_per_night'] . "<br>";
}
?>

</section>