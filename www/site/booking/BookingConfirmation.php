<?php
require_once __DIR__ . '/../../../privat/db.php';

class BookingConfirmation {
    public function getBookingDetails($bookingId) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT b.*, r.room_type, r.price_per_night 
                                FROM bookings b 
                                JOIN rooms r ON b.room_id = r.id 
                                WHERE b.id = ?");
        $stmt->execute([$bookingId]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT preference_key, preference_value FROM preferences WHERE booking_id = ?");
        $stmt->execute([$bookingId]);
        $preferences = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['booking' => $booking, 'preferences' => $preferences];
    }
}

$confirmation = new BookingConfirmation();
$booking;