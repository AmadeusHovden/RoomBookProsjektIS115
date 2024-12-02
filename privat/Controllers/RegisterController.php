<?php
class RegisterController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($name)) {
                $errors['name'] = "Navn er obligatorisk.";
            } elseif (!preg_match("/^[a-zA-ZæøåÆØÅ\s]+$/", $name)) {
                $errors['name_format'] = "Navn kan kun inneholde bokstaver og mellomrom.";
            }

            if (empty($email)) {
                $errors['email'] = "E-post er obligatorisk.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_format'] = "Ugyldig e-postadresse.";
            }

            if (empty($password)) {
                $errors['password'] = "Passord er obligatorisk.";
            }

            if ($password !== $confirmPassword) {
                $errors['password_match'] = "Passordene samsvarer ikke.";
            }

            if (empty($errors)) {
                try {
                    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    if ($stmt->fetchColumn() > 0) {
                        $errors['email_exists'] = "E-postadressen er allerede registrert.";
                    } else {
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'bruker')");
                        if ($stmt->execute([$name, $email, $hashedPassword])) {
                            $_SESSION['user_id'] = $this->pdo->lastInsertId();
                            $_SESSION['name'] = $name;
                            $_SESSION['role'] = 'bruker';
                            $_SESSION['email'] = $email;


                            header("Location: index.php?page=user/profile");
                            exit();
                        } else {
                            $errors['insert'] = "Innsetting feilet: " . implode(", ", $stmt->errorInfo());
                        }
                    }
                } catch (PDOException $e) {
                    $errors['database'] = "Databasefeil: " . $e->getMessage();
                }
            }
        }

        require '../privat/Views/pages/register.php';
    }
}