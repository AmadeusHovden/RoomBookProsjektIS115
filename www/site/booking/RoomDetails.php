<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Booking Detaljer</title>
</head>
<body>
    <h1>Fyll inn dine detaljer</h1>
    <form method="post" action="BookingHandler.php">
        <input type="hidden" name="room_id" value="<?= htmlspecialchars($_POST['room_id']) ?>">
        <input type="hidden" name="check_in" value="<?= htmlspecialchars($_POST['check_in']) ?>">
        <input type="hidden" name="check_out" value="<?= htmlspecialchars($_POST['check_out']) ?>">
        <input type="hidden" name="adults" value="<?= htmlspecialchars($_POST['adults']) ?>">
        <input type="hidden" name="children" value="<?= htmlspecialchars($_POST['children']) ?>">

        <label for="guest_name">Navn:</label>
        <input type="text" name="guest_name" required>
        <label for="guest_email">E-post:</label>
        <input type="email" name="guest_email" required>

        <h3>Preferanser</h3>
        <label for="floor">Etasje:</label>
        <input type="number" name="floor" min="1">
        <label for="near_elevator">Nær heis:</label>
        <input type="checkbox" name="near_elevator" value="1">

        <button type="submit">Fullfør booking</button>
    </form>
</body>
</html>
