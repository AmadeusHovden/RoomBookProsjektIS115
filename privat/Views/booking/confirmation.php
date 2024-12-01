<h1>Booking Bekreftet</h1>
<section class="hero">
    <p>Rom: <?php echo htmlspecialchars($room['name'] ?? 'Ikke spesifisert'); ?></p>
    <p>Fra: <?php echo htmlspecialchars($dateFrom); ?></p>
    <p>Til: <?php echo htmlspecialchars($dateTo); ?></p>
    <p>Totalt: <?php echo htmlspecialchars($totalPrice); ?> NOK</p>
    <p>Vi ser frem til å ønske deg velkommen!</p>
    <a href="index.php">Tilbake til forsiden</a>
</section>