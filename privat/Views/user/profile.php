<?php
session_start(); // Start session to access session variables

// Check if the user is logged in and has the role 'bruker'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bruker') {
    // Redirect to login page if not logged in or wrong role
    header("Location: index.php?page=login");
    exit();
}
?>
        <section class="hero">
        <h1>Velkommen, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
        <br>
            <h2>Dine detaljer</h2>
            <p><strong>Navn:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
            <p><strong>Rolle:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
            <p><strong>Bruker-ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
      <br>
            <h2>Handlinger</h2>
            <form method="POST" action="index.php?page=logout">
                <button type="submit" class="logout-button">Logg ut</button>
            </form>
        </section>
