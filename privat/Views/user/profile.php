<?php
// Sjekk om brukeren er logget inn og har rollen 'bruker'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bruker') {
    // Omdiriger til innloggingssiden hvis ikke logget inn eller feil rolle
    header("Location: index.php?page=login");
    exit();
}
?>
<section class="hero">
    <h1>Velkommen, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
    <br>
    <h2>Din profil</h2>
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


