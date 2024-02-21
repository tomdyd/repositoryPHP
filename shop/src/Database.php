<?php

namespace App;
class Database
{
    private string $hostname;
    private string $dbname;
    private string $username;
    private string $password;
    private \PDO $conn;

    public function __construct()
    {
        $this->hostname = 'localhost';
        $this->dbname = 'shop_database';
        $this->username = 'tomasz dyda';
        $this->password = 'hasloxD';

        try {
            $this->conn = new \PDO(
                "mysql:host=$this->hostname;dbname=$this->dbname",
                $this->username,
                $this->password
            );
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function select(string $table, string $condition = null)
    {
        $sql = "SELECT * FROM $table";
        if ($condition) {
            $sql .= " WHERE $condition";
        }

        try {
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute();
            $result = $stmt;
            return $result;
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function delete(string $table, string $condition)
    {
        $sql = "DELETE FROM " . $table . " WHERE " . $condition;

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function edit(string $table, string $condition, array $params)
    {
        $sql = "UPDATE $table SET";

        $fid = array();
        foreach ($params as $k => $v) {
            $fid[] = " `$k` = '$v'";
        }
        $sql .= implode(",", $fid);
        $sql .= " WHERE $condition";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function insert($table, $data)
    {
        // Przygotowanie kolumn i wartości dla SQL
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";

        // Skomponowanie i wykonanie zapytania SQL
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}