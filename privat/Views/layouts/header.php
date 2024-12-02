<header>
    <div class="header-left">
        <a href="index.php?page=home">
            <img src="img/logo.png" alt="Hotel Logo" class="logo">
        </a>
    </div>
    <nav class="header-right">
        <a href="index.php?page=allrooms"><h3>Rom</h3></a>
        <a href="index.php?page=about"><h3>Om oss</h3></a>
        <a href="index.php?page=contact"><h3>Kontakt</h3></a>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="index.php?page=<?php echo $_SESSION['role'] === 'admin' ? 'admin/profile' : 'user/profile'; ?>">
                <h3>🐻‍❄️<?php echo htmlspecialchars(explode(' ', $_SESSION['name'])[0]); ?>🐻‍❄️</h3>
            </a>
        <?php } else { ?>
            <a href="index.php?page=login"><h3>Logg Inn</h3></a>
        <?php } ?>
    </nav>
</header>
