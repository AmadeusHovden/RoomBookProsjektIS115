<h1>Logg Inn</h1>
<section class="hero">

<div class="hero-content">
    <h4>Logg inn eller Registrer bruker for å samle poeng</h4>
</div>

<?php if (!empty($errors)): ?>
    <div class="error-messages">
        <?php foreach ($errors as $error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="hero-content">
    <form method="POST" action="index.php?page=login">
        <input type="email" id="email" name="email" placeholder="E-post:" required 
               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"><br><br>

        <input type="password" id="password" name="password" placeholder="Passord:" required><br><br>

        <button type="submit" class="search-button">Logg Inn</button>
    </form>
</div>

<div class="hero-content">
    <a href="index.php?page=register" class="back-link">Registrer</a>
</div>

</section>