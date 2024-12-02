<?php

class AdminController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Metode for å vise adminpanelet
    public function showPanel() {
        // Sjekk at brukeren er admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit();
        }

        // Hent alle rom
        $roomsQuery = $this->pdo->query("SELECT * FROM rooms");
        $rooms = $roomsQuery->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../Views/admin/panel.php';
    }

    // Metode for å endre brukerrolle
    public function updateUserRole() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
            $email = trim($_POST['email']);
            try {
                $stmt = $this->pdo->prepare("UPDATE users SET role = 'admin' WHERE email = ?");
                $stmt->execute([$email]);

                if ($stmt->rowCount() > 0) {
                    $_SESSION['message'] = "Bruker med e-post $email er nå admin.";
                } else {
                    $_SESSION['message'] = "Fant ingen bruker med e-posten $email.";
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = "Databasefeil: " . $e->getMessage();
            }
        }

        header("Location: index.php?page=admin/panel");
        exit();
    }

    // Metode for å oppdatere romdetaljer
    public function updateRoomDetails() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['room_id'])) {
            $roomId = $_POST['room_id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price_per_night'];

            try {
                $stmt = $this->pdo->prepare("UPDATE rooms SET name = ?, description = ?, price_per_night = ? WHERE id = ?");
                $stmt->execute([$name, $description, $price, $roomId]);

                $_SESSION['message'] = "Romdetaljer oppdatert for rom-ID $roomId.";
            } catch (PDOException $e) {
                $_SESSION['message'] = "Databasefeil: " . $e->getMessage();
            }
        }

        header("Location: index.php?page=admin/panel");
        exit();
    }

    // Metode for å gjøre rom utilgjengelig
    public function markRoomUnavailable() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['room_id'])) {
            $roomId = $_POST['room_id'];
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $reason = $_POST['reason'];

            try {
                $stmt = $this->pdo->prepare("
                    INSERT INTO unavailable_dates (room_id, start_date, end_date, reason)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$roomId, $startDate, $endDate, $reason]);

                $_SESSION['message'] = "Rom-ID $roomId er nå utilgjengelig fra $startDate til $endDate.";
            } catch (PDOException $e) {
                $_SESSION['message'] = "Databasefeil: " . $e->getMessage();
            }
        }

        header("Location: index.php?page=admin/panel");
        exit();
    }
}