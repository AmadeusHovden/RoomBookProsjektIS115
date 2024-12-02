<h1>Booking Bekreftet</h1>
<section class="hero">
    <h2>Booking ID <?php echo htmlspecialchars($bookingID); ?></p></h2>
    <p>Rom: <?php echo htmlspecialchars($room['name'] ?? 'Ikke spesifisert'); ?></p>
    <p>Fra: <?php echo htmlspecialchars($dateFrom); ?></p>
    <p>Til: <?php echo htmlspecialchars($dateTo); ?></p>
    <p>Totalt: <?php echo htmlspecialchars($totalPrice); ?> NOK</p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>

    <p>Vi ser frem til å ønske deg velkommen!</p>
    <br>
    <a href="index.php">Tilbake til forsiden</a>
    <br>
    <a href="index.php?page=user/profile">Mine Bookinger</a>
    <br>
    <a href="index.php?page=booking/download&bookingID=<?php echo htmlspecialchars($bookingID); ?>" class="download-link">Last ned Booking Bekreftelse</a>

</section>