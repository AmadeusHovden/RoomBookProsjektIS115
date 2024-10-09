

<h1>Hotell til deg og dine</h1>
        <!-- må fikse så knapper er rette, den hvite boksen skal være i midten.-->
            <!-- også lage regler for antall gjester, og hviklke datoer som r godkjent, PHP?-->
            <section class="hero">
            <form class="booking-form" method="post">
                <div class="input-group">
                    <label for="date-from">Dato Fra:</label>
                    <input type="date" id="date-from" name="date-from" placeholder="Velg dato" required>
                </div>
                <div class="input-group">
                    <label for="date-to">Dato Til:</label>
                    <input type="date" id="date-to" name="date-to" placeholder="Velg dato" required>
                </div>
                <div class="input-group">
                    <label for="guests">Antall gjester</label>
                    <input type="number" id="guests" name="guests" placeholder="2" min="1" required>
                </div>
                <button type="submit" class="search-button">Søk</button>
            </form>
            <br>

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
                    // Her kan du legge inn kode for å lagre bookingdata i databasen.
                }
            }
            ?>
        </section>


        <script>
    document.addEventListener("DOMContentLoaded", function() {
        const today = new Date().toISOString().split('T')[0];
        const fromDateInput = document.getElementById("date-from");
        const toDateInput = document.getElementById("date-to");

        // Sett minimum dato for "Dato Fra" til dagens dato
        fromDateInput.setAttribute("min", today);

        // Oppdater minimumsverdi for "Dato Til" basert på valgt "Dato Fra"
        fromDateInput.addEventListener("change", function() {
            toDateInput.setAttribute("min", fromDateInput.value);
        });
    });

    document.querySelector('.booking-form').addEventListener('submit', function(event) {
        const dateFrom = new Date(document.getElementById('date-from').value);
        const dateTo = new Date(document.getElementById('date-to').value);
        const today = new Date();
        const guests = document.getElementById('guests').value;

        let errorMessage = '';

        // Sjekk at "Dato Fra" ikke er tidligere enn dagens dato
        if (dateFrom < today.setHours(0, 0, 0, 0)) {
            errorMessage += "Dato Fra kan ikke være tidligere enn dagens dato.<br>";
        }

        // Sjekk at "Dato Til" er senere enn "Dato Fra"
        if (dateTo <= dateFrom) {
            errorMessage += "Dato Til må være senere enn Dato Fra.<br>";
        }

        // Sjekk at antall gjester er gyldig
        if (guests < 1) {
            errorMessage += "Antall gjester må være minst 1.<br>";
        }

        // Hvis det er noen feil, stopp innsending av skjemaet
        if (errorMessage) {
            event.preventDefault(); // Stopper skjemaet fra å sende
            document.querySelector('.error-message').innerHTML = errorMessage; // Vis feilmeldinger
        }
    });
</script>