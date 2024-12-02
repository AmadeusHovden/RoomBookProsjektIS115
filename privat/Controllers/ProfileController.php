<?php
class ProfileController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Metode for å endre passord
    public function changePassword() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $message = ''; // Feedback to the user

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = trim($_POST['current_password'] ?? '');
            $newPassword = trim($_POST['new_password'] ?? '');
            $confirmNewPassword = trim($_POST['confirm_new_password'] ?? '');

            // Validation
            if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
                $message = "Alle felt må fylles ut.";
            } elseif ($newPassword !== $confirmNewPassword) {
                $message = "Det nye passordet og bekreftelsen samsvarer ikke.";
            } elseif (strlen($newPassword) < 6) {
                $message = "Det nye passordet må være minst 6 tegn langt.";
            } else {
                try {
                    $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$user || !password_verify($currentPassword, $user['password'])) {
                        $message = "Det nåværende passordet er feil.";
                    } else {
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

        include __DIR__ . '/../Views/user/change_password.php';
    }

    public function showProfile() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bruker') {
            header("Location: index.php?page=login");
            exit();
        }

        $userEmail = $_SESSION['email'];

        // Fetch bookings for logged-in users or those with the same email as guest
        $query = "
            SELECT id AS booking_id, room_id, guest_name, guest_email, check_in, check_out, total_price 
            FROM bookings 
            WHERE guest_email = :email OR user_id = :userId
            ORDER BY check_in ASC
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':email' => $userEmail, ':userId' => $_SESSION['user_id']]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Separate upcoming and past bookings
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

        // Pass bookings data to the view
        include __DIR__ . '/../Views/user/profile.php';
    }

    public function deleteBooking() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
            $bookingId = $_POST['booking_id'];
    
            try {
                // Delete the booking from the database
                $stmt = $this->pdo->prepare("DELETE FROM bookings WHERE id = :bookingId AND (guest_email = :email OR user_id = :userId)");
                $stmt->execute([
                    ':bookingId' => $bookingId,
                    ':email' => $_SESSION['email'],
                    ':userId' => $_SESSION['user_id']
                ]);
    
                if ($stmt->rowCount() > 0) {
                    $_SESSION['message'] = "Booking med ID $bookingId ble slettet.";
                } else {
                    $_SESSION['message'] = "Kunne ikke slette booking. Vennligst prøv igjen.";
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = "Databasefeil: " . $e->getMessage();
            }
        }
    
        header("Location: index.php?page=user/profile");
        exit();
    }

    // Metode for å laste opp profilbilde
    public function uploadProfileImage() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $message = ''; 
    $uploadDir = realpath(__DIR__ . '/../../uploads/profile_images/') . '/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/pjpeg'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
        $file = $_FILES['profile_image'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $message = "Upload error code: " . $file['error'];
        } elseif (!in_array($file['type'], $allowedTypes)) {
            $message = "Invalid file type. Allowed: JPEG, PNG, GIF.";
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $message = "File too large. Max size is 2MB.";
        } else {
            $fileName = 'profile_' . $_SESSION['user_id'] . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $filePath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                try {
                    $stmt = $this->pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                    if ($stmt->execute([$fileName, $_SESSION['user_id']])) {
                        $_SESSION['profile_image'] = $fileName;
                        $message = "Profile image updated!";
                    } else {
                        $message = "Failed to save image in database.";
                    }
                } catch (PDOException $e) {
                    $message = "Database error: " . $e->getMessage();
                }
            } else {
                $message = "Failed to move uploaded file.";
            }
        }
    }

    include __DIR__ . '/../Views/user/profile.php';
    exit();
}

    // Metode for å vise profilen
    public function viewProfile() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Hent brukerinformasjon fra databasen
        try {
            $stmt = $this->pdo->prepare("SELECT name, email, role, profile_image FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['profile_image'] = $user['profile_image'] ?? null;
            }
        } catch (PDOException $e) {
            echo "Databasefeil: " . $e->getMessage();
        }

        include __DIR__ . '/../Views/user/profile.php';
    }
}
