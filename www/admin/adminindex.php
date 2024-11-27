<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../privat/db.php'; // Riktig sti til db.php

// Variabler for feilmeldinger
$error = "";

// Behandle skjema når det sendes inn
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Søk etter bruker i databasen med rollen "admin"
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verifiser brukeren og passordet
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $user['name'];
        header("Location: dash/dashboard.php"); // Send admin til dashboard
        exit;
    } else {
        $error = "Feil e-post eller passord.";
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Innlogging</title>
    <link rel="stylesheet" href="css/adminstyles.css"> <!-- Korrigert CSS-sti -->
</head>
<body>
    <main class="hero" style="background-image: url('../img/bakgrunn.jpg'); background-size: cover;">
        <div class="contact-form">
            <h1>Admin Innlogging</h1>
            <form action="adminindex.php" method="POST">
                <?php if ($error): ?>
                    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <div class="form-group">
                    <label for="email">E-post:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Passord:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="search-button">Logg inn</button>
            </form>
        </div>
    </main>
</body>
</html>
