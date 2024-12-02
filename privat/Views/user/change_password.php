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
        <p>Fyll ut skjemaet for å oppdatere passordet ditt:</p>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=change_password">
            <div class="form-group">
                <label for="current_password">Nåværende passord:</label>
                <input type="password" id="current_password" name="current_password" placeholder="Nåværende passord" required>
            </div>
            <div class="form-group">
                <label for="new_password">Nytt passord:</label>
                <input type="password" id="new_password" name="new_password" placeholder="Nytt passord" required>
            </div>
            <div class="form-group">
                <label for="confirm_new_password">Bekreft nytt passord:</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="Bekreft nytt passord" required>
            </div>
            <button type="submit" class="save-password-button">Lagre nytt passord</button>
        </form>
        <br>
        <a href="index.php?page=user/profile" class="back-link">Tilbake til profilen</a>
    </section>
</body>
</html>
