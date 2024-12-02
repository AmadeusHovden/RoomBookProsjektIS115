<?php

class BookingController {
    private $roomsModel;

    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/RoomsModel.php';
        $this->roomsModel = new RoomsModel($pdo);
    }

    public function search() {
        $dateFrom = $_POST['date_from'] ?? null;
        $dateTo = $_POST['date_to'] ?? null;
        $adults = $_POST['adults'] ?? null;
        $children = isset($_POST['children']) && $_POST['children'] !== '' ? $_POST['children'] : 0; // Default to 0

        // Validate required fields
        if (!$dateFrom || !$dateTo || !$adults) {
            return ['error' => 'Vennligst fyll inn alle nødvendige feltene (Dato Fra, Dato Til og Antall Voksne).'];
        }

        // Get available rooms from the model
        return $this->roomsModel->getAvailableRooms($dateFrom, $dateTo, $adults, $children);
    }

    public function details() {
        $roomId = $_POST['room_id'];
        $dateFrom = $_POST['date_from'];
        $dateTo = $_POST['date_to'];

        $room = $this->roomsModel->getRoomDetails($roomId);

        $days = (strtotime($dateTo) - strtotime($dateFrom)) / (60 * 60 * 24);
        $totalPrice = $days * $room['price_per_night'];

        require __DIR__ . '/../Views/booking/details.php';
    }

    public function confirmation() {
        $roomId = $_POST['room_id'] ?? null;
        $dateFrom = $_POST['date_from'] ?? $_POST['date_from'] ?? null;
        $dateTo = $_POST['date_to'] ?? $_POST['date_to'] ?? null;
        $adults = $_POST['adults'] ?? 1; // Default to 1 adult
        $children = $_POST['children'] ?? 0; // Default to 0 children
        $totalPrice = $_POST['total_price'] ?? null;
    
        $guestName = $_SESSION['name'] ?? ($_POST['guest_name'] ?? null);
        $guestEmail = $_SESSION['email'] ?? ($_POST['guest_email'] ?? null);
    
        if (!$roomId || !$dateFrom || !$dateTo || !$totalPrice || (!$guestName && !$guestEmail)) {
            die("Feil: Mangler nødvendige data for å fullføre booking.");
        }
    
        global $pdo;
    
        // Fetch room details
        $room = $this->roomsModel->getRoomDetails($roomId);
    
        // Insert booking into the database
        $query = "
            INSERT INTO bookings (user_id, guest_name, guest_email, room_id, check_in, check_out, adults, children, total_price)
            VALUES (:userId, :guestName, :guestEmail, :roomId, :checkIn, :checkOut, :adults, :children, :totalPrice)
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':userId' => $_SESSION['user_id'] ?? null,
            ':guestName' => $guestName,
            ':guestEmail' => $guestEmail,
            ':roomId' => $roomId,
            ':checkIn' => $dateFrom,
            ':checkOut' => $dateTo,
            ':adults' => $adults,
            ':children' => $children,
            ':totalPrice' => $totalPrice,
        ]);
    
        // Pass data to the confirmation view
        require __DIR__ . '/../Views/booking/confirmation.php';
    }
}