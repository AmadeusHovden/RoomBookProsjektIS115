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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once '../privat/config.php';
    // models
    require_once '../privat/Models/db.php';
    require_once '../privat/Models/RoomsModel.php';
    // controllers
    require_once '../privat/Controllers/LoginController.php';
    require_once '../privat/Controllers/LogoutController.php';
    require_once '../privat/Controllers/BookingController.php';
    require_once '../privat/Controllers/RegisterController.php';
    require_once '../privat/Controllers/ProfileController.php';

    $page = $_GET['page'] ?? 'home';

    switch ($page) {
        case 'home':
            $availableRooms = [];
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Henter tilgjengelige rom fra BookingController
                $controller = new BookingController($pdo);
                $availableRooms = $controller->search();
            }
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
            // Instantiate LoginController with $pdo
            $loginController = new LoginController($pdo);
            $loginController->login();
            break;

        case 'logout':
            // Instantiate LogoutController
            $logoutController = new LogoutController();
            $logoutController->logout();
            break;

            case 'register':
                $registerController = new RegisterController($pdo);
                $registerController->register();
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

        case 'booking/details':
            $controller = new BookingController($pdo);
            $controller->details();
            break;

            case 'booking/confirmation':
                $controller = new BookingController($pdo);
                $controller->confirmation();
                break;
        
          case 'change_password':
            $profileController = new ProfileController($pdo);
            $profileController->changePassword();
        break;

        default:
            echo "<h1>404 - Page Not Found</h1>";
            break;
    }
    ?>
    </main>

    <?php include '../privat/Views/layouts/footer.php'; ?>
</body>
</html>
