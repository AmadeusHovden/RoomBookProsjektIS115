<?php

use setasign\Fpdi\Fpdi;

class BookingController {
    private $roomsModel;

    // Konstruktør for å initialisere roomsModel med databaseforbindelsen
    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/RoomsModel.php';
        $this->roomsModel = new RoomsModel($pdo);
    }

    // Metode for å søke etter tilgjengelige rom
    public function search() {
        // Henter verdier fra skjemaet og setter standardverdier for barn
        $dateFrom = $_POST['date_from'] ?? null;
        $dateTo = $_POST['date_to'] ?? null;
        $adults = $_POST['adults'] ?? null;
        $children = isset($_POST['children']) && $_POST['children'] !== '' ? $_POST['children'] : 0;

        $errors = []; // Liste for å samle valideringsfeil

        // Validering av datoer
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

        // Validering av antall voksne og barn
        if (!$adults || $adults < 1) {
            $errors[] = 'Antall voksne må være minst 1.';
        }

        if ($children < 0) {
            $errors[] = 'Antall barn kan ikke være negativt.';
        }

        // Returner feil hvis noen finnes
        if (!empty($errors)) {
            return ['error' => implode('<br>', $errors)];
        }

        // Henter tilgjengelige rom fra modellen
        return $this->roomsModel->getAvailableRooms($dateFrom, $dateTo, $adults, $children);
    }

    // Metode for å vise detaljer for et spesifikt rom
    public function details() {
        // Henter verdier fra skjemaet
        $roomId = $_POST['room_id'];
        $dateFrom = $_POST['date_from'];
        $dateTo = $_POST['date_to'];

        // Henter romdetaljer fra modellen
        $room = $this->roomsModel->getRoomDetails($roomId);

        // Kalkulerer totalpris basert på antall dager
        $days = (strtotime($dateTo) - strtotime($dateFrom)) / (60 * 60 * 24);
        $totalPrice = $days * $room['price_per_night'];

        // Inkluderer visningen for detaljer
        require __DIR__ . '/../Views/booking/details.php';
    }

    // Metode for å bekrefte en booking
    public function confirmation() {
        // Henter verdier fra skjemaet eller session
        $roomId = $_POST['room_id'] ?? null;
        $dateFrom = $_POST['date_from'] ?? null;
        $dateTo = $_POST['date_to'] ?? null;
        $adults = $_POST['adults'] ?? 1;
        $children = $_POST['children'] ?? 0;
        $totalPrice = $_POST['total_price'] ?? null;

        $guestName = $_SESSION['name'] ?? ($_POST['guest_name'] ?? null);
        $guestEmail = $_SESSION['email'] ?? ($_POST['guest_email'] ?? null);

        // Validerer nødvendige data
        if (!$roomId || !$dateFrom || !$dateTo || !$totalPrice || (!$guestName && !$guestEmail)) {
            die("Feil: Mangler nødvendige data for å fullføre booking.");
        }

        global $pdo;

        // Henter romdetaljer
        $room = $this->roomsModel->getRoomDetails($roomId);

        // Setter inn booking i databasen
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

        $bookingID = $pdo->lastInsertId(); // Henter ID for den nyeste bookingen

        // Viser bekreftelsessiden
        require __DIR__ . '/../Views/booking/confirmation.php';
    }

    // Metode for å laste ned PDF-bekreftelse
    public function downloadConfirmationPDF($bookingID) {
        require_once __DIR__ . '/../../vendor/autoload.php';
        global $pdo;

        // Henter booking fra databasen
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$bookingID]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        // Hvis ingen booking finnes, stopp med feilmelding
        if (!$booking) {
            die("Booking ikke funnet.");
        }

        $generatedPath = __DIR__ . '/../Generated/';
        if (!file_exists($generatedPath)) {
            mkdir($generatedPath, 0777, true); // Lager mappen hvis den ikke finnes
        }

        // Oppretter PDF med FPDI
        $pdf = new Fpdi();
        $pdf->AddPage();

        $templatePath = __DIR__ . '/../Templates/fakturamal.pdf';
        $pdf->setSourceFile($templatePath);
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx);

        $logoPath = __DIR__ . '/../../www/img/logo2.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 10, 50); // Legger til logo
        }

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);

        // Legger til bookingdetaljer i PDF-en
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

        $pdfOutputPath = $generatedPath . "booking_{$bookingID}.pdf";
        $pdf->Output($pdfOutputPath, 'F');

        // Gjør PDF tilgjengelig for nedlasting
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="booking_confirmation.pdf"');
        readfile($pdfOutputPath);
    }
}