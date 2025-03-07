<?php

class Database
{
    private static $host = "localhost";
    private static $port = "3306";
    private static $username = "root";
    private static $password = "root";
    private static $dbname = "NADSOFT";
    private static $conn = null;

    // Create connection
    public static function getConnection()
    {

        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname,
                    self::$username,
                    self::$password
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection Error: " . $e->getMessage();
                die();
            }
        }
        return self::$conn;

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            echo "success";

            $createTableSQL = "
   CREATE TABLE IF NOT EXISTS Members (
        Id INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(50) NOT NULL,
        ParentId INT DEFAULT 0,
        CreatedDate DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (ParentId) REFERENCES Members(Id) ON DELETE CASCADE
    )";

            if ($conn->query($createTableSQL) === TRUE) {
                echo "Table created successfully";
            } else {
                echo "Error creating table: " . $conn->error;
            }

            
        }
    }
}
