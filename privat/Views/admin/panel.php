<section class="hero">
    <h1>Adminpanel</h1>

    <!-- Tilbakemelding -->
    <?php if (!empty($_SESSION['message'])): ?>
        <p><strong><?php echo htmlspecialchars($_SESSION['message']); ?></strong></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Endre brukerrolle -->
    <h2>Endre Brukerrolle</h2>
    <form method="POST" action="index.php?page=admin/updateUserRole">
        <label for="email">Brukerens E-post:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit" class="search-button">Gjør til Admin</button>
    </form>
    <hr>
    <br>

    <!-- Liste over rom -->
    <h2>Rediger Romdetaljer</h2>
    <ul>
        <?php foreach ($rooms as $room): ?>
            <li>
                <form method="POST" action="index.php?page=admin/updateRoomDetails">
                    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['id']); ?>">
                    <label for="name_<?php echo $room['id']; ?>">Navn:</label>
                    <input type="text" id="name_<?php echo $room['id']; ?>" name="name" value="<?php echo htmlspecialchars($room['name']); ?>" required>

                    <label for="description_<?php echo $room['id']; ?>">Beskrivelse:</label>
                    <input type="text" id="description_<?php echo $room['id']; ?>" name="description" value="<?php echo htmlspecialchars($room['description']); ?>" required>

                    <label for="price_<?php echo $room['id']; ?>">Pris per natt:</label>
                    <input type="number" id="price_<?php echo $room['id']; ?>" name="price_per_night" value="<?php echo htmlspecialchars($room['price_per_night']); ?>" required>

                    <button type="submit" class="search-button">Oppdater</button>
                </form>

                <!-- Gjør rom utilgjengelig -->
                <form method="POST" action="index.php?page=admin/markRoomUnavailable">
                    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['id']); ?>">
                    <label for="start_date_<?php echo $room['id']; ?>">Startdato:</label>
                    <input type="date" id="start_date_<?php echo $room['id']; ?>" name="start_date" required>

                    <label for="end_date_<?php echo $room['id']; ?>">Sluttdato:</label>
                    <input type="date" id="end_date_<?php echo $room['id']; ?>" name="end_date" required>

                    <label for="reason_<?php echo $room['id']; ?>">Årsak:</label>
                    <input type="text" id="reason_<?php echo $room['id']; ?>" name="reason" required>

                    <button type="submit" class="search-button">Marker som utilgjengelig</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</section>