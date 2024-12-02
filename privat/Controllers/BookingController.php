<?php

use setasign\Fpdi\Fpdi;

class BookingController {
    private $roomsModel;

    // Konstruktør som initialiserer RoomsModel med PDO-tilkoblingen
    public function __construct($pdo) {
        require_once __DIR__ . '/../Models/RoomsModel.php';
        $this->roomsModel = new RoomsModel($pdo);
    }

    // Metode for å søke etter ledige rom basert på brukerens kriterier
    public function search() {
        // Hent verdier fra POST-forespørsel eller sett standardverdier
        $dateFrom = $_POST['date_from'] ?? null;
        $dateTo = $_POST['date_to'] ?? null;
        $adults = $_POST['adults'] ?? null;
        $children = isset($_POST['children']) && $_POST['children'] !== '' ? $_POST['children'] : 0;

        // Validering av input
        $errors = [];
        if (!$dateFrom || !$dateTo) {
            $errors[] = 'Vennligst fyll inn både Dato Fra og Dato Til.';
        } else {
            $dateFromObj = new DateTime($dateFrom);
            $dateToObj = new DateTime($dateTo);
            $today = new DateTime();

            // Sjekk at "Dato Fra" ikke er tidligere enn i dag
            if ($dateFromObj < $today->setTime(0, 0, 0)) {
                $errors[] = 'Dato Fra kan ikke være tidligere enn dagens dato.';
            }

            // Sjekk at "Dato Til" er senere enn "Dato Fra"
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

        // Returner feilmeldinger hvis validering feiler
        if (!empty($errors)) {
            return ['error' => implode('<br>', $errors)];
        }

        // Hent ledige rom fra modellen
        return $this->roomsModel->getAvailableRooms($dateFrom, $dateTo, $adults, $children);
    }

    // Metode for å vise detaljer om et valgt rom
    public function details() {
        $roomId = $_POST['room_id'];
        $dateFrom = $_POST['date_from'];
        $dateTo = $_POST['date_to'];

        // Hent detaljer om rommet
        $room = $this->roomsModel->getRoomDetails($roomId);

        // Beregn antall dager og totalpris
        $days = (strtotime($dateTo) - strtotime($dateFrom)) / (60 * 60 * 24);
        $totalPrice = $days * $room['price_per_night'];

        // Vis detaljer i "details"-visningen
        require __DIR__ . '/../Views/booking/details.php';
    }

    // Metode for å fullføre en booking og lagre den i databasen
    public function confirmation() {
        $roomId = $_POST['room_id'] ?? null;
        $dateFrom = $_POST['date_from'] ?? null;
        $dateTo = $_POST['date_to'] ?? null;
        $adults = $_POST['adults'] ?? 1; // Standard 1 voksen
        $children = $_POST['children'] ?? 0; // Standard 0 barn
        $totalPrice = $_POST['total_price'] ?? null;

        // Hent brukerens navn og e-post
        $guestName = $_SESSION['name'] ?? ($_POST['guest_name'] ?? null);
        $guestEmail = $_SESSION['email'] ?? ($_POST['guest_email'] ?? null);

        // Sjekk at nødvendige data er til stede
        if (!$roomId || !$dateFrom || !$dateTo || !$totalPrice || (!$guestName && !$guestEmail)) {
            die("Feil: Mangler nødvendige data for å fullføre booking.");
        }

        global $pdo;

        // Hent detaljer om rommet
        $room = $this->roomsModel->getRoomDetails($roomId);

        // Sett inn bookingdata i databasen
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

        // Hent ID-en til den nylig opprettede bookingen
        $bookingID = $pdo->lastInsertId();

        // Vis bekreftelsessiden
        require __DIR__ . '/../Views/booking/confirmation.php';
    }

    // Metode for å generere og laste ned en PDF med bookingdetaljer
    public function downloadConfirmationPDF($bookingID) {
        require_once __DIR__ . '/../../vendor/autoload.php';
        global $pdo;

        // Hent bookingdetaljer fra databasen
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$bookingID]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            die("Booking ikke funnet.");
        }

        // Sørg for at mappen for genererte PDF-er eksisterer
        $generatedPath = __DIR__ . '/../Generated/';
        if (!file_exists($generatedPath)) {
            mkdir($generatedPath, 0777, true);
        }

        // Opprett PDF
        $pdf = new Fpdi();
        $pdf->AddPage();

        // Importer mal
        $templatePath = __DIR__ . '/../Templates/fakturamal.pdf';
        $pdf->setSourceFile($templatePath);
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx);

        // Legg til logo
        $logoPath = __DIR__ . '/../../www/img/logo2.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 10, 50);
        }

        // Skriv inn bookingdetaljer
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);
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

        // Lagre PDF-en
        $pdfOutputPath = $generatedPath . "booking_{$bookingID}.pdf";
        $pdf->Output($pdfOutputPath, 'F');

        // Send PDF-en til nedlasting
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="booking_confirmation.pdf"');
        readfile($pdfOutputPath);
    }
}
