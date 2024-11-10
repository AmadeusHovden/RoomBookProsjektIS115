<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../privat/db.php';

// Hent rominformasjon fra POST-forespørselen og sjekk at variabler er satt
$roomType = $_POST['room_type'] ?? '';
$dateFrom = $_POST['date_from'] ?? '';
$dateTo = $_POST['date_to'] ?? '';
$adults = $_POST['adults'] ?? 1;
$children = $_POST['children'] ?? 0;
$pricePerNight = $_POST['price_per_night'] ?? 0;

// Beregn totalpris basert på antall netter og pris per natt
$dateFromObj = new DateTime($dateFrom);
$dateToObj = new DateTime($dateTo);
$interval = $dateFromObj->diff($dateToObj);
$numberOfNights = $interval->days;
$totalPrice = $numberOfNights * $pricePerNight;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_booking'])) {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $nearElevator = $_POST['near_elevator'] ?? 'no';
    $floorPreference = $_POST['floor_preference'] ?? '1';

    // Hvis brukeren er logget inn, bruk bruker-ID-en fra session, ellers opprett en gjestebruker
    $userId = $_SESSION['user_id'] ?? null;

    try {
        $pdo->beginTransaction();
        if ($userId === null) {
            $sql = "INSERT INTO guests (first_name, last_name, phone, email) VALUES (:first_name, :last_name, :phone, :email)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':first_name' => $firstName, ':last_name' => $lastName, ':phone' => $phone, ':email' => $email]);
            $userId = $pdo->lastInsertId();
        }

        // Lagre booking i bookings-tabellen
        $sql = "INSERT INTO bookings (user_id, room_type, check_in, check_out, adults, children, total_price) 
                VALUES (:user_id, :room_type, :check_in, :check_out, :adults, :children, :total_price)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':room_type' => $roomType,
            ':check_in' => $dateFrom,
            ':check_out' => $dateTo,
            ':adults' => $adults,
            ':children' => $children,
            ':total_price' => $totalPrice
        ]);

        $bookingId = $pdo->lastInsertId();

        // Lagre preferanser i preferences-tabellen
        $sql = "INSERT INTO preferences (booking_id, near_elevator, floor_preference) VALUES (:booking_id, :near_elevator, :floor_preference)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':booking_id' => $bookingId, ':near_elevator' => $nearElevator, ':floor_preference' => $floorPreference]);

        $pdo->commit();

        // Lagre bookingdata i sesjonen for bekreftelsessiden
        $_SESSION['room_type'] = ucfirst($roomType);
        $_SESSION['price_per_night'] = $pricePerNight;
        $_SESSION['date_from'] = $dateFrom;
        $_SESSION['date_to'] = $dateTo;
        $_SESSION['number_of_nights'] = $numberOfNights;
        $_SESSION['adults'] = $adults;
        $_SESSION['children'] = $children;
        $_SESSION['total_price'] = $totalPrice;

        // Omdiriger til bekreftelsessiden
        header("Location: booking_confirmed.php");
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p>Noe gikk galt med bookingen: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<h1>Fullfør booking for <?php echo ucfirst($roomType); ?></h1>

<section class="hero">
    <p>Vennligst bekreft dine bookingdetaljer og fyll ut nødvendig informasjon for å fullføre bookingen.</p>

    <!-- Oppsummering av bookingdetaljer -->
    <div class="booking-summary">
        <h2>Bookingdetaljer</h2>
        <p><strong>Romtype:</strong> <?php echo ucfirst($roomType); ?></p>
        <p><strong>Pris per natt:</strong> <?php echo $pricePerNight; ?> kr</p>
        <p><strong>Dato fra:</strong> <?php echo $dateFrom; ?></p>
        <p><strong>Dato til:</strong> <?php echo $dateTo; ?></p>
        <p><strong>Antall netter:</strong> <?php echo $numberOfNights; ?></p>
        <p><strong>Antall voksne:</strong> <?php echo $adults; ?></p>
        <p><strong>Antall barn:</strong> <?php echo $children; ?></p>
        <p><strong>Totalpris:</strong> <?php echo $totalPrice; ?> kr</p>
    </div>

    <form method="post" action="'index.php?page=site/booking/booking_details'" class="contact-form">
        <!-- Skjulte felter for å overføre data -->
        <input type="hidden" name="room_type" value="<?php echo htmlspecialchars($roomType); ?>">
        <input type="hidden" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>">
        <input type="hidden" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>">
        <input type="hidden" name="adults" value="<?php echo htmlspecialchars($adults); ?>">
        <input type="hidden" name="children" value="<?php echo htmlspecialchars($children); ?>">
        <input type="hidden" name="price_per_night" value="<?php echo htmlspecialchars($pricePerNight); ?>">

        <?php if (!isset($_SESSION['user_id'])): ?>
            <!-- Felter for gjestebruker -->
            <div class="form-group">
                <label for="first_name">Fornavn:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Etternavn:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="phone">Telefonnummer:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="email">E-post:</label>
                <input type="email" id="email" name="email" required>
            </div>
        <?php endif; ?>

        <!-- Preferanser -->
        <div class="form-group">
            <label for="near_elevator">Nær heis:</label>
            <select id="near_elevator" name="near_elevator">
                <option value="yes">Ja</option>
                <option value="no">Nei</option>
            </select>
        </div>
        <div class="form-group">
            <label for="floor_preference">Etasje:</label>
            <select id="floor_preference" name="floor_preference">
                <option value="1">1. etasje</option>
                <option value="2">2. etasje</option>
            </select>
        </div>

        <button type="submit" name="confirm_booking" class="search-button">Fullfør booking</button>
    </form>
</section>
