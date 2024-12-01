<?php
// db.php - Kobler til MySQL-databasen ved hjelp av PDO

$host = 'localhost'; // Databasevert
$db = 'room_booking_system'; // Databasenavn
$user = 'root'; // Standardbrukeren i XAMPP
$pass = 'Sander123'; // Standardpassord for root-brukeren i XAMPP

try {
    // Opprette en ny PDO-tilkobling til databasen
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Sett feilhåndtering til unntak for enkel debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Bekreftelse på at tilkoblingen er vellykket
    //echo "Tilkobling til databasen var vellykket! <br><br>";
} catch (PDOException $e) {
    // Hvis tilkoblingen feiler, skriv ut feilmeldingen
    die("Feil med tilkobling: " . $e->getMessage());
}