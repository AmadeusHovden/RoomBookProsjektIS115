<h1>Booking Bekreftet</h1>
<section class="hero">
    <!-- Viser booking-ID som ble opprettet -->
    <h2>Booking ID <?php echo htmlspecialchars($bookingID); ?></h2>

    <!-- Viser navnet på rommet som ble booket -->
    <p>Rom: <?php echo htmlspecialchars($room['name'] ?? 'Ikke spesifisert'); ?></p>

    <!-- Viser innsjekksdato -->
    <p>Fra: <?php echo htmlspecialchars($dateFrom); ?></p>

    <!-- Viser utsjekksdato -->
    <p>Til: <?php echo htmlspecialchars($dateTo); ?></p>

    <!-- Viser totalprisen for bookingen -->
    <p>Totalt: <?php echo htmlspecialchars($totalPrice); ?> NOK</p>

    <!-- Viser e-posten som ble brukt for bookingen -->
    <p><strong>Email:</strong> 
        <?php 
            // Sjekker om brukeren booket som gjest eller er innlogget
            if (!empty($_POST['guest_email'])) {
                // Hvis brukeren er gjest, vis gjestens e-post fra skjemaet
                echo htmlspecialchars($_POST['guest_email']);
            } elseif (!empty($_SESSION['email'])) {
                // Hvis brukeren er innlogget, vis e-posten fra sesjonen
                echo htmlspecialchars($_SESSION['email']);
            } else {
                // Hvis ingen e-post er tilgjengelig, vis en standardtekst
                echo 'Ikke tilgjengelig';
            }
        ?>
    </p>

    <!-- En standard velkomstmelding -->
    <p>Vi ser frem til å ønske deg velkommen!</p>
    <br>

    <!-- Link for å navigere tilbake til forsiden -->
    <a href="index.php">Tilbake til forsiden</a>
    <br>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Hvis brukeren er innlogget, vis lenken til "Mine Bookinger" -->
        <a href="index.php?page=user/profile">Mine Bookinger</a>
        <br>
    <?php endif; ?>

    <!-- Link for å laste ned bookingbekreftelsen som PDF -->
    <a href="index.php?page=booking/download&bookingID=<?php echo htmlspecialchars($bookingID); ?>" class="download-link">Last ned Booking Bekreftelse</a>
</section>