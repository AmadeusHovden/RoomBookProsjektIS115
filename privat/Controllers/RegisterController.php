<?php
class RegisterController {
    public function register() {
        require_once '../privat/Models/db.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Validering
            if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
                echo "Alle felt må fylles ut.";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Ugyldig e-postadresse.";
                return;
            }

            if ($password !== $confirmPassword) {
                echo "Passordene samsvarer ikke.";
                return;
            }

            // Sjekk om e-posten allerede finnes
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                echo "E-postadressen er allerede registrert.";
                return;
            }

            // Krypter passordet
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Sett inn brukeren i databasen
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'bruker')");
            if ($stmt->execute([$name, $email, $hashedPassword])) {
                echo "Registrering vellykket! Du kan nå logge inn.";
                header("Location: index.php?page=login");
                exit();
            } else {
                echo "Noe gikk galt. Prøv igjen senere.";
            }
        } else {
            // Vis registreringsskjemaet
            require '../privat/Views/pages/register.php';
        }
    }
}