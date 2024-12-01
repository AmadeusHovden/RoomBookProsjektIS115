<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include '../privat/Views/layouts/header.php'; ?>

    <main>
        <?php
        // Aktiver feilvisning for debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Start session for å håndtere brukerinnlogging
        // Sjekk om økten allerede er startet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


        // Inkluder nødvendige kontroller
        require_once '../privat/Controllers/LoginController.php';
        require_once '../privat/Controllers/LogoutController.php';
        require_once '../privat/Controllers/RegisterController.php';

        // Hent siden fra GET-parameter eller bruk 'home' som standard
        $page = $_GET['page'] ?? 'home';

        // Routing basert på siden som er valgt
        switch ($page) {
            case 'home':
                require '../privat/Views/pages/home.php';
                break;

            case 'allrooms':
                require '../privat/Views/pages/allrooms.php';
                break;

            case 'about':
                require '../privat/Views/pages/about.php';
                break;

            case 'contact':
                require '../privat/Views/pages/contact.php';
                break;

            case 'login':
                $controller = new LoginController();
                $controller->login();
                break;

            case 'logout':
                $controller = new LogoutController();
                $controller->logout();
                break;

            case 'register':
                // Kall RegisterController for å håndtere registrering
                $controller = new RegisterController();
                $controller->register();
                break;

            case 'admin/profile':
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    require '../privat/Views/admin/profile.php';
                } else {
                    header("Location: index.php?page=login");
                    exit();
                }
                break;

            case 'user/profile':
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'bruker') {
                    require '../privat/Views/user/profile.php';
                } else {
                    header("Location: index.php?page=login");
                    exit();
                }
                break;

            default:
                echo '<h1>404 - Page Not Found</h1>';
                break;
        }
        ?>
    </main>

    <?php include '../privat/Views/layouts/footer.php'; ?>
</body>
</html>
