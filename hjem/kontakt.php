
<!--KAN LEGGE TIL MASSE PHP HER SOM VALIDERER FORMEN OG FAKTISK SENDER MAIL.  -->

<h1>Kontakt oss</h1>

<section class="hero">
    <p>Føl deg fri til å kontakte oss når som helst.</p>
    <form action="send_mail.php" method="post" class="contact-form">
        <div class="form-group">
            <label for="name">Navn:</label>
            <input type="text" id="name" name="name" placeholder="Ditt navn" required>
        </div>
        <div class="form-group">
            <label for="email">E-post:</label>
            <input type="email" id="email" name="email" placeholder="Din e-post" required>
        </div>
        <div class="form-group">
            <label for="subject">Hva gjelder det?</label>
            <select id="subject" name="subject" required>
                <option value="booking">Booking</option>
                <option value="spørsmål">Generelle spørsmål</option>
                <option value="klage">Klage</option>
                <option value="annet">Annet</option>
            </select>
        </div>
        <div class="form-group">
            <label for="message">Beskrivelse:</label>
            <textarea id="message" name="message" rows="5" placeholder="Skriv din melding her..." required></textarea>
        </div>

        <button type="submit" class="search-button">Send</button>

    </form>
</section>
