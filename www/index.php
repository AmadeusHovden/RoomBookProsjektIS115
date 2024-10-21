<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link rel="stylesheet" href="site/css/styles.css">
</head>
<body>
<body>
    <?php include 'site/inc/header.php'; ?>

    <main>
        <?php
        // Henter "page" paramter fra URL'en, default er hjem/home
        $page = isset($_GET['page']) ? $_GET['page'] : 'site/hjem/home';

        // Renser inputen for å forhindre angreå, (EKSTRA)
        $page = str_replace(['../', '..\\', './', '.\\'], '', $page);

        // Konstruerer filbanen
        $file = $page . '.php';

        // Sjekker om filen finnes, før den inkluderer
        if (file_exists($file)) {
            include $file;
        } else {
            // Viser 404 error siden, eller default siden.
            echo '<h1>404 Finner ikke sia</h1>';
        }
        ?>
    </main>

    <?php include 'site/inc/footer.php'; ?>
</body>
</html>

