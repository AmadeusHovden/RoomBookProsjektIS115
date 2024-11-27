<?php
require_once __DIR__ . '/../../../privat/db.php';

class BookingHandler {
    public function createBooking($roomId, $checkIn, $checkOut, $adults, $children, $guestName, $guestEmail, $preferences = []) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT price_per_night FROM rooms WHERE id = ?");
        $stmt->execute([$roomId]);
        $room = $stmt->fetch();
        $nights = (new DateTime($checkIn))->diff(new DateTime($checkOut))->days;
        $totalPrice = $nights * $room['price_per_night'];

        $stmt = $pdo->prepare("INSERT INTO bookings (room_id, check_in, check_out, adults, children, guest_name, guest_email, total_price) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$roomId, $checkIn, $checkOut, $adults, $children, $guestName, $guestEmail, $totalPrice]);
        $bookingId = $pdo->lastInsertId();

        foreach ($preferences as $key => $value) {
            if ($value !== null) {
                $stmt = $pdo->prepare("INSERT INTO preferences (booking_id, preference_key, preference_value) VALUES (?, ?, ?)");
                $stmt->execute([$bookingId, $key, $value]);
            }
        }

        return $bookingId;
    }
}

$handler = new BookingHandler();
$preferences = [
    'floor' => $_POST['floor'] ?? null,
    'near_elevator' => isset($_POST['near_elevator']) ? '1' : null
];
$bookingId = $handler->createBooking($_POST['room_id'], $_POST['check_in'], $_POST['check_out'], $_POST['adults'], $_POST['children'], $_POST['guest_name'], $_POST['guest_email'], $preferences);
header("Location: BookingConfirmation.php?booking_id=$bookingId");
exit;
