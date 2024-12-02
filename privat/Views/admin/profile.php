<?php

// Check if the user is logged in and has the role 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in or wrong role
    header("Location: index.php?page=login");
    exit();
}
?>
        <section class="hero">
        <h1>Velkommen, Admin <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
        <br>
            <h2>Dine detaljer</h2>
            <p><strong>Navn:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
            <p><strong>Rolle:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
            <p><strong>Bruker-ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
    <br>
            <h2>Handlinger</h2>
            <!-- Knapp for å navigere til passordendringsskjemaet -->
            <form method="GET" action="index.php">
                <input type="hidden" name="page" value="change_password">
                <button type="submit" class="search-button">Endre passord</button>
            </form>
            <br>
            <!-- Knapp for å logge ut -->
            <form method="POST" action="index.php?page=logout">
                <button type="submit" class="search-button">Logg ut</button>
            </form>
        </section>