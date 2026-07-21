<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    function connect_db() {
        $host = "localhost";
        $db_name = "wnj_web_v2";
        $username = "root";
        $password = "y6rI1W9dgu7Vb0lw31LPLW6k7fHZdkbK";

        try {
            $conn = new PDO("mysql:host=$host;dbname=$db_name;password=$password", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo json_encode(["message" => "Database connection successfull"]);
            return $conn;
        } catch (PDOException $exception) {
            http_response_code(500);
            echo json_encode(["message" => "Connection Error: " . $exception->getMessage()]);
            exit();
        }
    }

    connect_db();
?>