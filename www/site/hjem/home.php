<h1>Hotell til deg og dine</h1>

<section class="hero">
    <!-- Endre action slik at skjemaet sender brukeren til riktig URL -->
    <form class="booking-form" method="post" action="index.php?page=site/pages/booking">
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
            <label for="guests">Antall gjester:</label>
            <input type="number" id="guests" name="guests" placeholder="2" min="1" required
                   value="<?php echo isset($_POST['guests']) ? $_POST['guests'] : ''; ?>">
        </div>

        <button type="submit" class="search-button">Søk</button>
    </form>

    <br>

    <!-- PHP-koden som validerer skjemaet -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $dateFrom = $_POST['date-from'];
        $dateTo = $_POST['date-to'];
        $guests = $_POST['guests'];
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
        // Sjekk at antall gjester er gyldig
        if ($guests < 1) {
            $errorMessage .= "Antall gjester må være minst 1.<br>";
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
