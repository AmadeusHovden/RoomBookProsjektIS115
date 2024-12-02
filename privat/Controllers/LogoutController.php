<?php
class LogoutController {
    // Metode for å logge ut brukeren
    public function logout() {
        session_start(); // Starter økten

        session_unset();  // Fjerner alle sesjonsvariabler som er satt
       

        session_destroy(); // Ødelegger selve økten
       

        // Omdirigerer brukeren til innloggingssiden
        header("Location: index.php?page=login");
        exit(); // Sørger for at ingen ytterligere kode kjører etter omdirigeringen
    }
}
