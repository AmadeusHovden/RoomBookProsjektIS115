<section class="hero">
  
<div class="profile-container">
    <!-- Profilbilde Seksjon -->
    <div class="profile-image-section">
        <h1>Velkommen, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
        <?php if (!empty($_SESSION['profile_image'])): ?>
            <img src="../uploads/profile_images/<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profilbilde" class="profile-image">
        <?php else: ?>
            <p>Du har ikke lastet opp et profilbilde ennå.</p>
        <?php endif; ?>
    </div>

    <?php if (empty($_SESSION['profile_image'])): ?>
        <!-- Skjema for opplasting av profilbilde -->
        <div class="upload-section">
            <h2>Last opp nytt profilbilde</h2>
            <form method="POST" action="index.php?page=upload_profile_image" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" id="profile_image" name="profile_image" accept="image/*" required>
                </div>
                <button type="submit" class="search-button">Last opp bilde</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Tilbakemelding for opplasting -->
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
</div>

        <!-- Tilbakemelding for opplasting -->
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>

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
