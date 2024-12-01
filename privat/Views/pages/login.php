<?php
session_start();
?>
<h1>Logg Inn</h1>
<section class="hero">

<div class="hero-content">
    <h4>Logg inn eller Registrer bruker for å samle poeng</h4>
</div>

<div class="hero-content">
    <form method="POST" action="index.php?page=login">
        <label for="email">E-post:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Passord:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit" class="search-button">Logg Inn</button>
    </form>
    <?php if (isset($message)) { ?>
        <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
    <?php } ?>
</div>

<div class="hero-content">
    <a href="index.php?page=register" class="register-link">Registrer</a>
</div>

</section>