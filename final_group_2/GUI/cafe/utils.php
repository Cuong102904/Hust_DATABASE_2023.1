<?php
    function dd($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }

    function sanitize($data) {
        $data = trim($data);
        stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function get_next_staff_id($pdo) {
        $sql = 'SELECT MAX(staff_id) FROM staff';
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_COLUMN);
    
        // If no staff exists, start from 1, otherwise, increment the maximum staff ID
        return $result ? $result + 1 : 1;
    }
// utils.php

function fetch_select_options($pdo, $table, $valueColumn, $labelColumn)
{
    try {
        $sql = "SELECT $valueColumn, $labelColumn FROM $table";
        $stmt = $pdo->query($sql);

        if ($stmt) {
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $options;
        } else {
            die("Error fetching select options for $table");
        }
    } catch (PDOException $e) {
        die('Error fetching select options: ' . $e->getMessage());
    }
}
?>




