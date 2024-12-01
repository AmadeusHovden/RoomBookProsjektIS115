<h1>Booking</h1>

<section class="hero">
    <!-- Skjema som sender brukeren til riktig URL -->
    <form class="booking-form" method="post" action="index.php?page=site/booking/booking">
        <div class="input-group">
            <label for="date-from">Dato Fra:</label>
            <input type="date" id="date-from" name="date-from" placeholder="Velg dato" required
                   value="<?php echo isset($_POST['date-from']) ? $_POST['date-from'] : ''; ?>">
        </div>

        <div class="input-group">
            <label for="date-to">Dato Til:</label>
            <input type="date" id="date-to" name="date-to" placeholder="Velg dato" required
                   value="<?php echo isset($_POST['date-to']) ? $_POST['date-to'] : ''; ?>">
        </div>

        <div class="input-group">
            <label for="adults">Antall voksne:</label>
            <input type="number" id="adults" name="adults" placeholder="1" min="1" required
                   value="<?php echo isset($_POST['adults']) ? $_POST['adults'] : ''; ?>">
        </div>
        
        <div class="input-group">
            <label for="children">Antall barn:</label>
            <input type="number" id="children" name="children" placeholder="0" min="0" required
                   value="<?php echo isset($_POST['children']) ? $_POST['children'] : '0'; ?>">
        </div>

        <button type="submit" class="search-button">Søk</button>
    </form>

    <?php if (isset($availableRooms)) { ?>
        <h2>Tilgjengelige rom:</h2>
        <ul>
            <?php foreach ($availableRooms as $room) { ?>
                <li><?php echo $room['name']; ?> (Kapasitet: <?php echo $room['capacity']; ?>)</li>
            <?php } ?>
        </ul>
    <?php } ?>
</section>

<!-- JavaScript for å sikre at datoene er gyldige -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const today = new Date().toISOString().split('T')[0];
        const fromDateInput = document.getElementById("date-from");
        const toDateInput = document.getElementById("date-to");

        fromDateInput.setAttribute("min", today);

        fromDateInput.addEventListener("change", function() {
            const fromDateValue = new Date(fromDateInput.value);
            const minToDate = new Date(fromDateValue);
            minToDate.setDate(minToDate.getDate() + 1);
            toDateInput.setAttribute("min", minToDate.toISOString().split('T')[0]);
        });
    });
</script>
