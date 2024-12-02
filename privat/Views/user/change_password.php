<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endre Passord</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <section class="hero">
        <h1>Endre passord</h1>
        <br>
        <p>Fyll ut skjemaet for å oppdatere passordet ditt:</p>
        <br>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=change_password">
            <div class="form-group">
                <input type="password" id="current_password" name="current_password" placeholder="Nåværende passord" required>
            </div>
            <br>
            <div class="form-group">
                <input type="password" id="new_password" name="new_password" placeholder="Nytt passord" required>
            </div>
            <br>
            <div class="form-group">
                <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="Bekreft nytt passord" required>
            </div>
            <br>
            <button type="submit" class="search-button">Lagre nytt passord</button>
        </form>
        <br>
        <?php
            $role = $_SESSION['role'] ?? null; // Retrieve the user's role from the session
            $profilePage = $role === 'admin' ? 'admin/profile' : 'user/profile'; // Determine the profile page based on role
            ?>
            <a href="index.php?page=<?php echo htmlspecialchars($profilePage); 
            ?>" class="back-link">Tilbake til profilen</a>
    </section>
</body>
</html>
