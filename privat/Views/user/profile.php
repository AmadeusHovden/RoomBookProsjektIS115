<?php
// Sjekk om brukeren er logget inn og har rollen 'bruker'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bruker') {
    // Omdiriger til innloggingssiden hvis ikke logget inn eller feil rolle
    header("Location: index.php?page=login");
    exit();
}
?>
<section class="hero">
    <div class="profile-container">
        <!-- Profilbilde Seksjon -->
        <div class="profile-image-section">
            <h1>Velkommen, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
            <?php if (!empty($_SESSION['profile_image'])): ?>
                <img src="../uploads/profile_images/<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profilbilde" class="profile-image">
            <?php else: ?>
                <p>Du har ikke lastet opp et profilbilde ennå.</p>
            <?php endif; ?>
        </div>

        <!-- Skjema for opplasting av profilbilde -->
        <div class="upload-section">
            <h2>Last opp nytt profilbilde</h2>
            <form method="POST" action="index.php?page=upload_profile_image" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="profile_image">Velg bilde:</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*" required>
                </div>
                <button type="submit" class="search-button">Last opp bilde</button>
            </form>
        </div>

        <!-- Tilbakemelding for opplasting -->
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>

    <div class="details-section">
        <!-- Bruker Detaljer -->
        <h2>Din profil</h2>
        <p><strong>Navn:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
        <p><strong>Rolle:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
        <p><strong>Bruker-ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
    </div>

    <div class="actions-section">
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
    </div>
</section>
