<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<body>
    <?php include 'header.php'; ?>

    <main>
        <?php
        // Get the 'page' parameter from the URL, default to 'hjem/home' if not set
        $page = isset($_GET['page']) ? $_GET['page'] : 'hjem/home';

        // Sanitize the input to prevent directory traversal attacks
        $page = str_replace(['../', '..\\', './', '.\\'], '', $page);

        // Construct the file path
        $file = $page . '.php';

        // Check if the file exists before including
        if (file_exists($file)) {
            include $file;
        } else {
            // Display a 404 error or a default page
            echo '<h1>404 Page not found</h1>';
        }
        ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
