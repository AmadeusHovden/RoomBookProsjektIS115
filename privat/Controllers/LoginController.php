<?php
class LoginController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validate inputs
            if (empty($email)) {
                $errors['email'] = "E-post er obligatorisk.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_format'] = "Ugyldig e-postadresse.";
            }

            if (empty($password)) {
                $errors['password'] = "Passord er obligatorisk.";
            }

            // Proceed only if no errors
            if (empty($errors)) {
                try {
                    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                        // Account locked check
                        if ($user['failed_attempts'] >= 3 && strtotime($user['lock_time']) > time() - 300) {
                            $remainingTime = 300 - (time() - strtotime($user['lock_time']));
                            $errors['locked'] = "Kontoen er låst. Prøv igjen om " . ceil($remainingTime / 60) . " minutter.";
                        } elseif (password_verify($password, $user['password'])) {
                            // Reset failed attempts and login
                            $stmt = $this->pdo->prepare("UPDATE users SET failed_attempts = 0, lock_time = NULL WHERE id = ?");
                            $stmt->execute([$user['id']]);

                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['name'] = $user['name'];
                            $_SESSION['role'] = $user['role'];

                            // Redirect
                            $redirectPage = $user['role'] === 'admin' ? 'admin/profile' : 'user/profile';
                            header("Location: index.php?page=$redirectPage");
                            exit();
                        } else {
                            // Increment failed attempts
                            $stmt = $this->pdo->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, lock_time = IF(failed_attempts >= 2, NOW(), NULL) WHERE id = ?");
                            $stmt->execute([$user['id']]);
                            $errors['invalid'] = "Feil e-post eller passord.";
                        }
                    } else {
                        $errors['not_found'] = "Bruker ikke funnet.";
                    }
                } catch (PDOException $e) {
                    $errors['database'] = "Databasefeil: " . $e->getMessage();
                }
            }
        }

        // Include login form with errors
        require '../privat/Views/pages/login.php';
    }
}