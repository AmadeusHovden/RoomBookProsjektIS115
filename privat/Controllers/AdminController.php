<?php

class AdminController {
    private $pdo; // Databaseforbindelse

    public function __construct($pdo) {
        $this->pdo = $pdo; // Initialiserer databaseforbindelsen
    }

    // Metode for å vise adminpanelet
    public function showPanel() {
        // Sjekker om brukeren er admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            // Hvis brukeren ikke er admin, omdirigeres til innloggingssiden
            header("Location: index.php?page=login");
            exit();
        }

        // Henter alle rom fra databasen
        $roomsQuery = $this->pdo->query("SELECT * FROM rooms");
        $rooms = $roomsQuery->fetchAll(PDO::FETCH_ASSOC); // Henter resultatene som en assosiativ tabell

        // Inkluderer visningen for adminpanelet
        include __DIR__ . '/../Views/admin/panel.php';
    }

    // Metode for å endre en brukers rolle
    public function updateUserRole() {
        // Sjekker om skjemaet er sendt via POST og om e-postadressen er oppgitt
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
            $email = trim($_POST['email']); // Trimmer eventuell ekstra mellomrom
            try {
                // Oppdaterer rollen til bruker med spesifisert e-postadresse til 'admin'
                $stmt = $this->pdo->prepare("UPDATE users SET role = 'admin' WHERE email = ?");
                $stmt->execute([$email]);

                // Sjekker om noen rader ble oppdatert
                if ($stmt->rowCount() > 0) {
                    $_SESSION['message'] = "Bruker med e-post $email er nå admin.";
                } else {
                    $_SESSION['message'] = "Fant ingen bruker med e-posten $email.";
                }
            } catch (PDOException $e) {
                // Fanger opp databasefeil og lagrer en feilmelding i sesjonen
                $_SESSION['message'] = "Databasefeil: " . $e->getMessage();
            }
        }

        // Omdirigerer tilbake til adminpanelet
        header("Location: index.php?page=admin/panel");
        exit();
    }

    // Metode for å oppdatere detaljer for et rom
    public function updateRoomDetails() {
        // Sjekker om skjemaet er sendt via POST og om rom-ID er oppgitt
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['room_id'])) {
            $roomId = $_POST['room_id']; // Henter rom-ID fra skjemaet
            $name = $_POST['name']; // Henter nytt navn
            $description = $_POST['description']; // Henter ny beskrivelse
            $price = $_POST['price_per_night']; // Henter ny pris

            try {
                // Oppdaterer romdetaljene i databasen
                $stmt = $this->pdo->prepare("UPDATE rooms SET name = ?, description = ?, price_per_night = ? WHERE id = ?");
                $stmt->execute([$name, $description, $price, $roomId]);

                // Lagrer en suksessmelding i sesjonen
                $_SESSION['message'] = "Romdetaljer oppdatert for rom-ID $roomId.";
            } catch (PDOException $e) {
                // Fanger opp databasefeil og lagrer en feilmelding i sesjonen
                $_SESSION['message'] = "Databasefeil: " . $e->getMessage();
            }
        }

        // Omdirigerer tilbake til adminpanelet
        header("Location: index.php?page=admin/panel");
        exit();
    }

    // Metode for å gjøre et rom utilgjengelig
    public function markRoomUnavailable() {
        // Sjekker om skjemaet er sendt via POST og om rom-ID er oppgitt
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['room_id'])) {
            $roomId = $_POST['room_id']; // Henter rom-ID fra skjemaet
            $startDate = $_POST['start_date']; // Henter startdato
            $endDate = $_POST['end_date']; // Henter sluttdato
            $reason = $_POST['reason']; // Henter årsak

            try {
                // Setter inn utilgjengelighetsperiode i databasen
                $stmt = $this->pdo->prepare("
                    INSERT INTO unavailable_dates (room_id, start_date, end_date, reason)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$roomId, $startDate, $endDate, $reason]);

                // Lagrer en suksessmelding i sesjonen
                $_SESSION['message'] = "Rom-ID $roomId er nå utilgjengelig fra $startDate til $endDate.";
            } catch (PDOException $e) {
                // Fanger opp databasefeil og lagrer en feilmelding i sesjonen
                $_SESSION['message'] = "Databasefeil: " . $e->getMessage();
            }
        }

        // Omdirigerer tilbake til adminpanelet
        header("Location: index.php?page=admin/panel");
        exit();
    }
}