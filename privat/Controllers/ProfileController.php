<?php
class ProfileController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

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
}

