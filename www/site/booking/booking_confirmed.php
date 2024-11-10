<?php
session_start();
?>

<h1>Booking Bekreftet</h1>

<section class="hero">
    <p>Takk for din booking! Din booking er nå bekreftet.</p>

    <div class="booking-summary">
        <h2>Bookingdetaljer</h2>
        <p><strong>Romtype:</strong> <?php echo htmlspecialchars($_SESSION['room_type']); ?></p>
        <p><strong>Pris per natt:</strong> <?php echo htmlspecialchars($_SESSION['price_per_night']); ?> kr</p>
        <p><strong>Dato fra:</strong> <?php echo htmlspecialchars($_SESSION['date_from']); ?></p>
        <p><strong>Dato til:</strong> <?php echo htmlspecialchars($_SESSION['date_to']); ?></p>
        <p><strong>Antall netter:</strong> <?php echo htmlspecialchars($_SESSION['number_of_nights']); ?></p>
        <p><strong>Antall voksne:</strong> <?php echo htmlspecialchars($_SESSION['adults']); ?></p>
        <p><strong>Antall barn:</strong> <?php echo htmlspecialchars($_SESSION['children']); ?></p>
        <p><strong>Totalpris:</strong> <?php echo htmlspecialchars($_SESSION['total_price']); ?> kr</p>

        <h3>Kontakt Oss</h3>
        <p>Har du spørsmål om din booking? <a href="index.php?page=site/hjem/contact">Kontakt oss her</a>.</p>
    </div>
</section>
