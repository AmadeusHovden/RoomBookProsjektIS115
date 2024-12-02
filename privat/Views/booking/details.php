<h1>Detaljer om Booking</h1>
<section class="hero">
    <h2>Rom: <?php echo htmlspecialchars($room['name']); ?></h2>
    <br>
    <p>Beskrivelse: <?php echo htmlspecialchars($room['description']); ?></p>
    <br>
    <p>Totalt: <?php echo number_format($totalPrice, 2, ',', ' '); ?> NOK</p>
    <br>

    <?php if (!isset($_SESSION['user_id'])) { ?>
        <!-- Guest Form -->
        <form method="post" action="index.php?page=booking/confirmation">
            <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['id']); ?>">
            <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($totalPrice); ?>">
            <input type="hidden" name="date_from" value="<?php echo htmlspecialchars($dateFrom ?? ''); ?>">
            <input type="hidden" name="date_to" value="<?php echo htmlspecialchars($dateTo ?? ''); ?>">
            <input type="hidden" name="adults" value="<?php echo htmlspecialchars($adults ?? '1'); ?>">
            <input type="hidden" name="children" value="<?php echo htmlspecialchars($children ?? '0'); ?>">

    <?php if (!isset($_SESSION['user_id'])) { ?>
        <label for="guest_name">Fullt Navn:</label>
        <input type="text" id="guest_name" name="guest_name" required>
        <label for="guest_email">E-post:</label>
        <input type="email" id="guest_email" name="guest_email" required>
    <?php } ?>

    <button type="submit" class="search-button">Fullfør Booking</button>
</form>
        
    <?php } else { ?>
        <!-- Logged-In User -->
        <form method="post" action="index.php?page=booking/confirmation">
            <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['id']); ?>">
            <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($totalPrice); ?>">
            <input type="hidden" name="date_from" value="<?php echo htmlspecialchars($dateFrom ?? ''); ?>">
            <input type="hidden" name="date_to" value="<?php echo htmlspecialchars($dateTo ?? ''); ?>">
            <input type="hidden" name="adults" value="<?php echo htmlspecialchars($adults ?? '1'); ?>">
            <input type="hidden" name="children" value="<?php echo htmlspecialchars($children ?? '0'); ?>">
            <button type="submit" class="search-button">Fullfør Booking</button>
        </form>
    <?php } ?>
</section>