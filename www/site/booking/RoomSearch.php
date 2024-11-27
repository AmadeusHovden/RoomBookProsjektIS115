<?php
require_once __DIR__ . '/../../../privat/db.php';

class RoomSearch {
    public function searchAvailableRooms($checkIn, $checkOut, $adults, $children) {
        global $pdo;
        $query = "SELECT * FROM rooms WHERE availability = 1 
                  AND max_adults >= ? AND max_children >= ?
                  AND id NOT IN (SELECT room_id FROM bookings WHERE (check_in <= ? AND check_out >= ?))";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$adults, $children, $checkOut, $checkIn]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$search = new RoomSearch();
$rooms = $search->searchAvailableRooms($_POST['check_in'], $_POST['check_out'], $_POST['adults'], $_POST['children']);
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Tilgjengelige Rom</title>
</head>
<body>
    <h1>Tilgjengelige Rom</h1>
    <?php if (!empty($rooms)): ?>
        <?php foreach ($rooms as $room): ?>
            <div class="room">
                <h3>Romtype: <?= htmlspecialchars($room['room_type']) ?> - Etasje: <?= $room['floor'] ?></h3>
                <p>Pris per natt: <?= $room['price_per_night'] ?> NOK</p>
                <!-- Knapp for å velge rom -->
                <form method="post" action="index.php?page=site/booking/RoomDetails">
                    <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                    <input type="hidden" name="check_in" value="<?= $_POST['check_in'] ?>">
                    <input type="hidden" name="check_out" value="<?= $_POST['check_out'] ?>">
                    <input type="hidden" name="adults" value="<?= $_POST['adults'] ?>">
                    <input type="hidden" name="children" value="<?= $_POST['children'] ?>">
                    <button type="submit">Velg rom</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Ingen ledige rom funnet.</p>
    <?php endif; ?>
</body>
</html>
