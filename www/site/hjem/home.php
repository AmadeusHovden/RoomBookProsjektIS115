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

    <br>

    <!-- PHP-koden som validerer skjemaet -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $dateFrom = $_POST['date-from'];
        $dateTo = $_POST['date-to'];
        $adults = $_POST['adults'];
        $children = $_POST['children'];
        $errorMessage = '';

        // Konverter datoene til DateTime-objekter
        $dateFromObj = new DateTime($dateFrom);
        $dateToObj = new DateTime($dateTo);
        $today = new DateTime();

        // Sjekk at "Dato Fra" ikke er tidligere enn dagens dato
        if ($dateFromObj < $today->setTime(0, 0, 0)) {
            $errorMessage .= "Dato fra kan ikke være tidligere enn dagens dato.<br>";
        }
        // Sjekk at "Dato Til" er senere enn "Dato Fra"
        if ($dateToObj <= $dateFromObj) {
            $errorMessage .= "Dato Til må være senere enn Dato Fra.<br>";
        }
        // Sjekk at antall voksne og barn er gyldig
        if ($adults < 1) {
            $errorMessage .= "Antall voksne må være minst 1.<br>";
        }
        if ($children < 0) {
            $errorMessage .= "Antall barn kan ikke være negativt.<br>";
        }

        // Hvis det er noen feil, vis dem
        if (!empty($errorMessage)) {
            echo "<div class='error-message'>$errorMessage</div>";
        } else {
            echo "Datoene og antall gjester er gyldige! Fortsett med booking.";
            // Hvis skjemaet er gyldig, sendes data til booking.php
        }
    }
    ?>
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
