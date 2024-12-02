<?php
class LoginController {
    private $pdo;

    // Konstruktør for å initialisere PDO-tilkoblingen
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Metode for å håndtere innlogging
    public function login() {
        $errors = []; // Array for å lagre feil

        // Sjekk om forespørselen er en POST (skjema sendt inn)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Hent e-post og passord fra skjemaet
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validering av e-post
            if (empty($email)) {
                $errors['email'] = "E-post er obligatorisk.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_format'] = "Ugyldig e-postadresse.";
            }

            // Validering av passord
            if (empty($password)) {
                $errors['password'] = "Passord er obligatorisk.";
            }

            // Fortsett kun hvis det ikke er noen feil
            if (empty($errors)) {
                try {
                    // Sjekk om brukeren finnes i databasen
                    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                        // Sjekk om kontoen er låst
                        if ($user['failed_attempts'] >= 3 && strtotime($user['lock_time']) > time() - 300) {
                            $remainingTime = 300 - (time() - strtotime($user['lock_time']));
                            $errors['locked'] = "Kontoen er låst. Prøv igjen om " . ceil($remainingTime / 60) . " minutter.";
                        } elseif (password_verify($password, $user['password'])) {
                            // Nullstill antall feilforsøk og logg inn brukeren
                            $stmt = $this->pdo->prepare("UPDATE users SET failed_attempts = 0, lock_time = NULL WHERE id = ?");
                            $stmt->execute([$user['id']]);

                            // Sett sesjonsvariabler
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['name'] = $user['name'];
                            $_SESSION['role'] = $user['role'];
                            $_SESSION['email'] = $user['email'];
                            error_log("Session set: " . print_r($_SESSION, true));

                            // Omdiriger brukeren basert på rolle
                            $redirectPage = $user['role'] === 'admin' ? 'admin/profile' : 'user/profile';
                            header("Location: index.php?page=$redirectPage");
                            exit();
                        } else {
                            // Øk antall feilforsøk hvis passordet er feil
                            $stmt = $this->pdo->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, lock_time = IF(failed_attempts >= 2, NOW(), NULL) WHERE id = ?");
                            $stmt->execute([$user['id']]);
                            $errors['invalid'] = "Feil e-post eller passord.";
                        }
                    } else {
                        // Brukeren finnes ikke i databasen
                        $errors['not_found'] = "Bruker ikke funnet.";
                    }
                } catch (PDOException $e) {
                    // Håndter databasefeil
                    $errors['database'] = "Databasefeil: " . $e->getMessage();
                }
            }
        }

        // Inkluder innloggingsskjema med eventuelle feil
        require '../privat/Views/pages/login.php';
    }
}
