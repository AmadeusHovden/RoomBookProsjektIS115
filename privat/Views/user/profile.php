<section class="hero">
    <h1>Velkommen, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
    <br>
    <h2>Din profil</h2>
    <p><strong>Navn:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    <br>

    <!-- Display success/error messages -->
    <?php if (!empty($_SESSION['message'])): ?>
        <p><strong><?php echo htmlspecialchars($_SESSION['message']); ?></strong></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <h2>Kommende Bookinger</h2>
    <?php if (!empty($upcomingBookings)): ?>
        <ul>
            <?php foreach ($upcomingBookings as $booking): ?>
                <li>
                    <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking['booking_id']); ?></p>
                    <p><strong>Rom ID:</strong> <?php echo htmlspecialchars($booking['room_id']); ?></p>
                    <p><strong>Navn:</strong> <?php echo htmlspecialchars($booking['guest_name']); ?></p>
                    <p><strong>Innsjekk:</strong> <?php echo htmlspecialchars($booking['check_in']); ?></p>
                    <p><strong>Utsjekk:</strong> <?php echo htmlspecialchars($booking['check_out']); ?></p>
                    <p><strong>Pris:</strong> <?php echo htmlspecialchars(number_format($booking['total_price'], 2, ',', ' ')); ?> NOK</p>
                    <a href="index.php?page=booking/download&bookingID=<?php echo htmlspecialchars($booking['booking_id']); ?>">Last ned bookingbekreftelse</a>
                    <!-- Delete button -->
                    <form method="POST" action="index.php?page=user/deleteBooking">
                        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                        <button type="submit" class="delete-button">Kanseller booking</button>
                    </form>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Ingen kommende bookinger funnet.</p>
    <?php endif; ?>

    <h2>Tidligere Bookinger</h2>
    <?php if (!empty($pastBookings)): ?>
        <ul>
            <?php foreach ($pastBookings as $booking): ?>
                <li>
                    <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking['booking_id']); ?></p>
                    <p><strong>Rom ID:</strong> <?php echo htmlspecialchars($booking['room_id']); ?></p>
                    <p><strong>Navn:</strong> <?php echo htmlspecialchars($booking['guest_name']); ?></p>
                    <p><strong>Innsjekk:</strong> <?php echo htmlspecialchars($booking['check_in']); ?></p>
                    <p><strong>Utsjekk:</strong> <?php echo htmlspecialchars($booking['check_out']); ?></p>
                    <p><strong>Pris:</strong> <?php echo htmlspecialchars(number_format($booking['total_price'], 2, ',', ' ')); ?> NOK</p>
                    <a href="index.php?page=booking/download&bookingID=<?php echo htmlspecialchars($booking['booking_id']); ?>">Last ned bookingbekreftelse</a>
                    <!-- Delete button -->
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Ingen tidligere bookinger funnet.</p>
    <?php endif; ?>

    <h2>Handlinger</h2>
    <form method="GET" action="index.php">
        <input type="hidden" name="page" value="change_password">
        <button type="submit" class="search-button">Endre passord</button>
    </form>
    <br>
    <form method="POST" action="index.php?page=logout">
        <button type="submit" class="search-button">Logg ut</button>
    </form>

</section>