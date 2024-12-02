<?php

// Check if the user is logged in and has the role 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in or wrong role
    header("Location: index.php?page=login");
    exit();
}
?>
<section class="hero">
    <h1>Velkommen, <?php echo htmlspecialchars($_SESSION['name']); ?> (Admin)!</h1>
    <br>

    <!-- Profilbilde Seksjon -->
    <div class="profile-container">
        <div class="profile-image-section">
            <?php if (!empty($_SESSION['profile_image'])): ?>
                <img src="../uploads/profile_images/<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profilbilde" class="profile-image">
            <?php else: ?>
                <p>Du har ikke lastet opp et profilbilde ennå.</p>
            <?php endif; ?>
        </div>

        <?php if (empty($_SESSION['profile_image'])): ?>
            <!-- Skjema for opplasting av profilbilde -->
            <div class="upload-section">
                <h2>Last opp nytt profilbilde</h2>
                <form method="POST" action="index.php?page=upload_profile_image" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" required>
                    </div>
                    <button type="submit" class="search-button">Last opp bilde</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <h2>Dine detaljer</h2>
    <p><strong>Navn:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
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