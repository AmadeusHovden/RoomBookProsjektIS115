<?php
class ProfileController {
    private $pdo;

    // Konstruktør for å initialisere PDO-tilkoblingen
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Metode for å endre passord
    public function changePassword() {
        // Start session hvis ikke allerede startet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $message = ''; // Feedback til brukeren

        // Sjekk om forespørselen er en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Hent data fra skjemaet
            $currentPassword = trim($_POST['current_password'] ?? '');
            $newPassword = trim($_POST['new_password'] ?? '');
            $confirmNewPassword = trim($_POST['confirm_new_password'] ?? '');

            // Validering av passord
            if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
                $message = "Alle felt må fylles ut.";
            } elseif ($newPassword !== $confirmNewPassword) {
                $message = "Det nye passordet og bekreftelsen samsvarer ikke.";
            } elseif (strlen($newPassword) < 6) {
                $message = "Det nye passordet må være minst 6 tegn langt.";
            } else {
                try {
                    // Hent det gjeldende passordet fra databasen
                    $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Sjekk om det oppgitte nåværende passordet er korrekt
                    if (!$user || !password_verify($currentPassword, $user['password'])) {
                        $message = "Det nåværende passordet er feil.";
                    } else {
                        // Krypter det nye passordet og oppdater databasen
                        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                        $updateStmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                        if ($updateStmt->execute([$hashedNewPassword, $_SESSION['user_id']])) {
                            $message = "Passordet er endret!";
                        } else {
                            $message = "Passordet kunne ikke oppdateres.";
                        }
                    }
                } catch (PDOException $e) {
                    $message = "Databasefeil: " . $e->getMessage();
                }
            }
        }

        // Inkluder visningen for å endre passord
        include __DIR__ . '/../Views/user/change_password.php';
    }

    // Metode for å vise brukerens profil og bookinger
    public function showProfile() {
        // Sjekk om brukeren er logget inn og har riktig rolle
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bruker') {
            header("Location: index.php?page=login");
            exit();
        }

        $userEmail = $_SESSION['email'];

        // Hent brukerens bookinger fra databasen
        $query = "
            SELECT id AS booking_id, room_id, guest_name, guest_email, check_in, check_out, total_price 
            FROM bookings 
            WHERE guest_email = :email OR user_id = :userId
            ORDER BY check_in ASC
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':email' => $userEmail, ':userId' => $_SESSION['user_id']]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Skille mellom fremtidige og tidligere bookinger
        $upcomingBookings = [];
        $pastBookings = [];
        $currentDate = new DateTime();

        foreach ($bookings as $booking) {
            $checkOutDate = new DateTime($booking['check_out']);
            if ($checkOutDate >= $currentDate) {
                $upcomingBookings[] = $booking;
            } else {
                $pastBookings[] = $booking;
            }
        }

        // Inkluder visningen for profil
        include __DIR__ . '/../Views/user/profile.php';
    }

    // Metode for å slette en booking
    public function deleteBooking() {
        // Sjekk om forespørselen er en POST og inneholder booking_id
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
            $bookingId = $_POST['booking_id'];

            try {
                // Slett bookingen fra databasen
                $stmt = $this->pdo->prepare("DELETE FROM bookings WHERE id = :bookingId AND (guest_email = :email OR user_id = :userId)");
                $stmt->execute([
                    ':bookingId' => $bookingId,
                    ':email' => $_SESSION['email'],
                    ':userId' => $_SESSION['user_id']
                ]);

                // Sjekk om slettingen var vellykket
                if ($stmt->rowCount() > 0) {
                    $_SESSION['message'] = "Booking med ID $bookingId ble slettet.";
                } else {
                    $_SESSION['message'] = "Kunne ikke slette booking. Vennligst prøv igjen.";
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = "Databasefeil: " . $e->getMessage();
            }
        }

        // Omdiriger til profilen etter sletting
        header("Location: index.php?page=user/profile");
        exit();
    }

    // Metode for å laste opp et profilbilde
    public function uploadProfileImage() {
        // Start session hvis ikke allerede startet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $message = ''; 
        $uploadDir = __DIR__ . '/../../uploads/profile_images/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        // Håndter opplastingsforespørselen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
            $file = $_FILES['profile_image'];

            // Valider filen
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $message = "Det oppstod en feil under opplastingen.";
            } elseif (!in_array($file['type'], $allowedTypes)) {
                $message = "Bare JPEG, PNG og GIF er tillatt.";
            } elseif ($file['size'] > 2 * 1024 * 1024) { 
                $message = "Filen er for stor. Maks 2MB.";
            } else {
                // Generer filnavn og flytt filen til riktig mappe
                $fileName = 'profile_' . $_SESSION['user_id'] . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    try {
                        // Oppdater databasen med filnavnet
                        $stmt = $this->pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                        if ($stmt->execute([$fileName, $_SESSION['user_id']])) {
                            $_SESSION['profile_image'] = $fileName; 
                            $message = "Profilbildet er oppdatert!";
                        } else {
                            $message = "Kunne ikke lagre bildet i databasen.";
                        }
                    } catch (PDOException $e) {
                        $message = "Databasefeil: " . $e->getMessage();
                    }
                } else {
                    $message = "Kunne ikke flytte filen.";
                }
            }
        }

        include __DIR__ . '/../Views/user/profile.php';
    }
}
