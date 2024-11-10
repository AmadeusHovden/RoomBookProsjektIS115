<h1>Tilgjengelige Rom</h1>

<section class="hero">
    <!-- Søkeformularet som vises på toppen med forhåndsutfylt brukerinput -->
    <form class="booking-form" method="post" action="index.php?page=site/pages/booking">
        <div class="input-group">
            <label for="date-from">Dato Fra:</label>
            <input type="date" id="date-from" name="date-from" required
                   value="<?php echo isset($_POST['date-from']) ? htmlspecialchars($_POST['date-from']) : ''; ?>">
        </div>

        <div class="input-group">
            <label for="date-to">Dato Til:</label>
            <input type="date" id="date-to" name="date-to" required
                   value="<?php echo isset($_POST['date-to']) ? htmlspecialchars($_POST['date-to']) : ''; ?>">
        </div>

        <div class="input-group">
            <label for="adults">Antall voksne:</label>
            <input type="number" id="adults" name="adults" min="1" required
                   value="<?php echo isset($_POST['adults']) ? htmlspecialchars($_POST['adults']) : '1'; ?>">
        </div>

        <div class="input-group">
            <label for="children">Antall barn:</label>
            <input type="number" id="children" name="children" min="0" required
                   value="<?php echo isset($_POST['children']) ? htmlspecialchars($_POST['children']) : '0'; ?>">
        </div>

        <button type="submit" class="search-button">Søk</button>
    </form>

    <br>

    <?php
    // Inkluderer databasen
    require_once '../privat/db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $dateFrom = $_POST['date-from'];
        $dateTo = $_POST['date-to'];
        $adults = $_POST['adults'];
        $children = $_POST['children'];

        $dateFromObj = new DateTime($dateFrom);
        $dateToObj = new DateTime($dateTo);
        $today = new DateTime();

        if ($dateFromObj < $today->setTime(0, 0, 0)) {
            echo "Dato fra kan ikke være tidligere enn dagens dato.<br>";
        } elseif ($dateToObj <= $dateFromObj) {
            echo "Dato til må være senere enn dato fra.<br>";
        } else {
            $sql = "SELECT room_type, COUNT(*) AS available_count, price_per_night 
                    FROM rooms 
                    WHERE max_adults >= :adults 
                    AND max_children >= :children 
                    AND availability = TRUE 
                    AND id NOT IN (
                        SELECT room_id FROM bookings 
                        WHERE (check_in < :dateTo AND check_out > :dateFrom)
                    )
                    GROUP BY room_type, price_per_night";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dateFrom', $dateFrom);
            $stmt->bindParam(':dateTo', $dateTo);
            $stmt->bindParam(':adults', $adults);
            $stmt->bindParam(':children', $children);
            $stmt->execute();

            $availableRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<div class='room-list'>";
            if (count($availableRooms) > 0) {
                foreach ($availableRooms as $room) {
                    $roomType = htmlspecialchars($room['room_type']);
                    $pricePerNight = htmlspecialchars($room['price_per_night']);
                    $availableCount = htmlspecialchars($room['available_count']);
                    $imageSrc = "site/img/{$roomType}.jpg";

                    $description = '';
                    if ($roomType === 'suite') {
                        $description = "Luksuriøs suite med moderne fasiliteter og stor plass.";
                    } elseif ($roomType === 'family') {
                        $description = "Komfortable familierom med plass til hele familien.";
                    } elseif ($roomType === 'double') {
                        $description = "Ideelle dobbeltrom for par eller venner.";
                    } elseif ($roomType === 'single') {
                        $description = "Koselige enkeltrom for de som reiser alene.";
                    }

                    echo "<div class='room'>
                            <div class='rom-content'>
                                <img src='$imageSrc' alt='$roomType' class='rom-bilde'>
                                <div class='romtekst'>
                                    <h2>" . ucfirst($roomType) . "</h2>
                                    <p>$description</p>
                                    <p><strong>Antall tilgjengelige:</strong> $availableCount</p>
                                    <p><strong>Pris per natt:</strong> $pricePerNight kr</p>
                                    <form action='index.php?page=site/booking/booking_details' method='post'>
                                        <input type='hidden' name='room_type' value='$roomType'>
                                        <input type='hidden' name='date_from' value='$dateFrom'>
                                        <input type='hidden' name='date_to' value='$dateTo'>
                                        <input type='hidden' name='adults' value='$adults'>
                                        <input type='hidden' name='children' value='$children'>
                                        <input type='hidden' name='price_per_night' value='$pricePerNight'>
                                        <button type='submit' class='room-button'>Velg rom</button>
                                    </form>
                                </div>
                            </div>
                          </div><br>";
                }
            } else {
                echo "<p>Ingen rom tilgjengelige for de valgte datoene og antall gjester.</p>";
            }
            echo "</div>";
        }
    } else {
        echo "<p>Ingen data mottatt. Vennligst gå tilbake til søkesiden og prøv igjen.</p>";
    }
    ?>
</section>

