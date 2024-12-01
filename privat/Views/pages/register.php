<h1>Registrer deg</h1>
<section class="hero">
    <form method="POST" action="index.php?page=register">
        <div class="form-group">
            <label for="name">Navn:</label>
            <input type="text" id="name" name="name" placeholder="Ditt navn" required>
        </div>
        <div class="form-group">
            <label for="email">E-post:</label>
            <input type="email" id="email" name="email" placeholder="Din e-post" required>
        </div>
        <div class="form-group">
            <label for="password">Passord:</label>
            <input type="password" id="password" name="password" placeholder="Velg et passord" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Bekreft passord:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Bekreft passordet" required>
        </div>
        <button type="submit" class="search-button">Registrer</button>
    </form>
</section>