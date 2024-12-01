<?php
class RegisterController {
    public function register() {
        // Sjekk om økten allerede er startet før du kaller session_start
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Oppdater filbanen til å være dynamisk
        require_once __DIR__ . '/../Models/db.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validering
            if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
                echo "<p style='color: red;'>Alle felt må fylles ut.</p>";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<p style='color: red;'>Ugyldig e-postadresse.</p>";
                return;
            }

            if ($password !== $confirmPassword) {
                echo "<p style='color: red;'>Passordene samsvarer ikke.</p>";
                return;
            }

            try {
                // Sjekk om e-posten allerede finnes
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    echo "<p style='color: red;'>E-postadressen er allerede registrert.</p>";
                    return;
                }

                // Krypter passordet
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Sett inn brukeren i databasen
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'bruker')");
                if ($stmt->execute([$name, $email, $hashedPassword])) {
                    // Få den nylig opprettede brukerens ID
                    $userId = $pdo->lastInsertId();

                    // Logg brukeren inn automatisk
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['name'] = $name;
                    $_SESSION['role'] = 'bruker'; // Brukerrollen fra spørringen

                    // Redirect til profilsiden basert på rollen
                    header("Location: index.php?page=user/profile");
                    exit();
                } else {
                    echo "<p style='color: red;'>Innsetting feilet: " . implode(", ", $stmt->errorInfo()) . "</p>";
                }
            } catch (PDOException $e) {
                echo "<p style='color: red;'>Databasefeil: " . $e->getMessage() . "</p>";
            }
        } else {
            // Oppdater filbanen for registreringsskjemaet
            require_once __DIR__ . '/../Views/pages/register.php';
        }
    }
}
