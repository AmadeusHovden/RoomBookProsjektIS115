<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-left">
            <img src="bilder/logo.png" alt="Hotel Logo" class="logo">
        </div>
        <nav class="header-right">
            <a href="om-oss.html">Om oss</a>
            <a href="kontakt.html">Kontakt</a>
        </nav>
    </header>
    <main>
        <h1>Hotell til deg og dine</h1>
        <!-- må fikse så knapper er rette, den hvite boksen skal være i midten.-->
            <!-- også lage regler for antall gjester, og hviklke datoer som r godkjent, PHP?-->
        <section class="hero">
            <form class="booking-form">
                <div class="input-group">
                    <label for="date">Dato Fra:</label>
                    <input type="date" id="date" placeholder="Velg dato">
                </div>
                <div class="input-group">
                    <label for="date">Dato Til:</label>
                    <input type="date" id="date" placeholder="Velg dato">
                </div>
                <div class="input-group">
                    <label for="guests">Antall gjester</label>
                    <input type="number" id="guests" placeholder="2">
                </div>
                <button type="submit" class="search-button">Søk</button>
            </form>
        </section>
    </main>

<!-- mer relevant i footer.-->
    <footer>
        <img src="bilder/logo2.png" alt="Hotel Logo" class="logo">
    </footer>
    
</body>
</html>
