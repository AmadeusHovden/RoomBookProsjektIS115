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

    // Metode for å laste opp profilbilde
    public function uploadProfileImage() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        $message = ''; 
        $uploadDir = __DIR__ . '/../../uploads/profile_images/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/pjpeg'];

    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
            $file = $_FILES['profile_image'];
    
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $message = "Det oppstod en feil under opplastingen.";
            } elseif (!in_array($file['type'], $allowedTypes)) {
                $message = "Bare JPEG, PNG og GIF er tillatt.";
            } elseif ($file['size'] > 2 * 1024 * 1024) { 
                $message = "Filen er for stor. Maks 2MB.";
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
