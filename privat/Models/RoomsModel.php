<?php

class RoomsModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAvailableRooms($dateFrom, $dateTo, $adults, $children) {
        $query = "
            SELECT *
            FROM rooms
            WHERE availability = 1
            AND max_adults >= :adults
            AND max_children >= :children
            AND id NOT IN (
                SELECT room_id
                FROM unavailable_dates
                WHERE start_date <= :dateTo AND end_date >= :dateFrom
            )
            AND id NOT IN (
                SELECT room_id
                FROM bookings
                WHERE check_in <= :dateTo AND check_out >= :dateFrom
            )
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':dateFrom' => $dateFrom,
            ':dateTo' => $dateTo,
            ':adults' => $adults,
            ':children' => $children,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomDetails($roomId) {
        $query = "SELECT * FROM rooms WHERE id = :roomId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':roomId' => $roomId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}