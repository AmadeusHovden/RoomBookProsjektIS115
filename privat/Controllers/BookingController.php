<?php

use setasign\Fpdi\Fpdi;

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
    
        // Backend validation
        $errors = [];
    
        if (!$dateFrom || !$dateTo) {
            $errors[] = 'Vennligst fyll inn både Dato Fra og Dato Til.';
        } else {
            $dateFromObj = new DateTime($dateFrom);
            $dateToObj = new DateTime($dateTo);
            $today = new DateTime();
    
            if ($dateFromObj < $today->setTime(0, 0, 0)) {
                $errors[] = 'Dato Fra kan ikke være tidligere enn dagens dato.';
            }
    
            if ($dateToObj <= $dateFromObj) {
                $errors[] = 'Dato Til må være senere enn Dato Fra.';
            }
        }
    
        if (!$adults || $adults < 1) {
            $errors[] = 'Antall voksne må være minst 1.';
        }
    
        if ($children < 0) {
            $errors[] = 'Antall barn kan ikke være negativt.';
        }
    
        if (!empty($errors)) {
            return ['error' => implode('<br>', $errors)];
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

        // Retrieve the last inserted booking ID
        $bookingID = $pdo->lastInsertId();
    
        // Pass data to the confirmation view
        require __DIR__ . '/../Views/booking/confirmation.php';
    }

    public function downloadConfirmationPDF($bookingID) {
        require_once __DIR__ . '/../../vendor/autoload.php';
        global $pdo;
    
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$bookingID]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$booking) {
            die("Booking ikke funnet.");
        }
    
        // Ensure Generated folder exists
        $generatedPath = __DIR__ . '/../Generated/';
        if (!file_exists($generatedPath)) {
            mkdir($generatedPath, 0777, true);
        }
    
        // Create PDF
        $pdf = new Fpdi();
        $pdf->AddPage();
    
        // Import the template
        $templatePath = __DIR__ . '/../Templates/fakturamal.pdf';
        $pdf->setSourceFile($templatePath);
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx);
    
        // Include logo
        $logoPath = __DIR__ . '/../../www/img/logo2.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 10, 50);
        }
    
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);
    
        // Insert booking details
        $pdf->SetXY(50, 50);
        $pdf->Write(10, "Booking ID: " . $booking['id']);
    
        $pdf->SetXY(50, 60);
        $pdf->Write(10, "Navn: " . $booking['guest_name']);
    
        $pdf->SetXY(50, 70);
        $pdf->Write(10, "E-post: " . $booking['guest_email']);
    
        $pdf->SetXY(50, 80);
        $pdf->Write(10, "Rom: " . $booking['room_id']);
    
        $pdf->SetXY(50, 90);
        $pdf->Write(10, "Fra: " . $booking['check_in']);
    
        $pdf->SetXY(50, 100);
        $pdf->Write(10, "Til: " . $booking['check_out']);
    
        $pdf->SetXY(50, 110);
        $pdf->Write(10, "Totalt: " . $booking['total_price'] . " NOK");
    
        // Save PDF to Generated folder
        $pdfOutputPath = $generatedPath . "booking_{$bookingID}.pdf";
        $pdf->Output($pdfOutputPath, 'F');
    
        // Send PDF for download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="booking_confirmation.pdf"');
        readfile($pdfOutputPath);
    }
}