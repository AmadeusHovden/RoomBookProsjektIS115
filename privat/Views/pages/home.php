<h1>Booking</h1>

<section class="hero">
    <form class="booking-form" method="post" action="index.php?page=home">
        <div class="input-group">
            <label for="date-from">Dato Fra:</label>
            <input type="date" id="date-from" name="date-from" placeholder="Velg dato" required
                   value="<?php echo htmlspecialchars($_POST['date-from'] ?? ''); ?>">
        </div>

        <div class="input-group">
            <label for="date-to">Dato Til:</label>
            <input type="date" id="date-to" name="date-to" placeholder="Velg dato" required
                   value="<?php echo htmlspecialchars($_POST['date-to'] ?? ''); ?>">
        </div>

        <div class="input-group">
            <label for="adults">Antall voksne:</label>
            <input type="number" id="adults" name="adults" placeholder="1" min="1" required
                   value="<?php echo htmlspecialchars($_POST['adults'] ?? ''); ?>">
        </div>
        
        <div class="input-group">
            <label for="children">Antall barn:</label>
            <input type="number" id="children" name="children" placeholder="0" min="0"
                   value="<?php echo htmlspecialchars($_POST['children'] ?? '0'); ?>">
        </div>

        <button type="submit" class="search-button">Søk</button>
    </form>
</section>

<!-- Display error if search fails -->
<?php if (!empty($searchError)) { ?>
    <p class="error"><?php echo $searchError; ?></p>
<?php } ?>

<!-- Display available rooms if search succeeds -->
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($availableRooms)) { ?>
    <h2>Tilgjengelige Rom</h2>
    <section class="rooms">
        <?php foreach ($availableRooms as $room) { ?>
            <div class="room">
                <div class="rom-content">
                    <img src="img/suite.jpg" alt="Rom bilde" class="rom-bilde">
                    <div class="romtekst">
                        <h2><?php echo htmlspecialchars($room['name']); ?></h2>
                        <p><?php echo htmlspecialchars($room['description']); ?></p>
                        <p>Maks voksne: <?php echo htmlspecialchars($room['max_adults']); ?></p>
                        <p>Maks barn: <?php echo htmlspecialchars($room['max_children']); ?></p>
                        <p>Pris per natt: <?php echo number_format($room['price_per_night'], 2, ',', ' '); ?> NOK</p>
                        <form method="post" action="index.php?page=booking/details">
                            <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['id']); ?>">
                            <input type="hidden" name="date-from" value="<?php echo htmlspecialchars($_POST['date-from']); ?>">
                            <input type="hidden" name="date-to" value="<?php echo htmlspecialchars($_POST['date-to']); ?>">
                            <button class="room-button">Velg Rom</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
<?php } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
    <p>Ingen tilgjengelige rom for de valgte datoene.</p>
<?php } ?>