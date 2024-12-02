<h1>Registrer deg</h1>
<section class="hero">
    <form method="POST" action="index.php?page=register">
        <div class="form-group">
            <input type="text" id="name" name="name" placeholder="Navn:" required
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>
        <br>
        <div class="form-group">
            <input type="email" id="email" name="email" placeholder="E-post:" required
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <br>
        <div class="form-group">
            <input type="password" id="password" name="password" placeholder="Velg et passord" required>
        </div>
        <br>
        <div class="form-group">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Bekreft passordet" required>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <br>
        <button type="submit" class="search-button">Registrer</button>
    </form>
</section>