<?php
require_once 'classes/Connection.php';

class User {
    public $id;
    public $username;
    public $password;
    public $country;
    public $fullName;
    public $birthDay;
    public $month;
    public $year;
    public $email;
    public $role_id;

    public function __construct() {
    }

    public function save() {
        $params = array(
            'username' => $this->username,
            'password' => $this->password,
            'country' => $this->country,
            'fullName' => $this->fullName,
            'birthDay' => $this->birthDay,
            'month' => $this->month,
            'year' => $this->year,
            'email' => $this->email,
            'role_id' => $this->role_id
        );

        if ($this->id === NULL) {
            $sql = "INSERT INTO users(
                        username, password, country, fullName, birthDay, month, year, email, role_id
                    ) VALUES (
                        :username, :password, :country, :fullName, :birthDay, :month, :year, :email, :role_id
                    )";
        }
        else if ($this->id !== NULL) {
            $params["id"] = $this->id;

            $sql = "UPDATE users SET
                        username = :username,
                        password = :password,
                        country = :country,
                        fullName = :fullName,
                        birthDay = :birthDay,
                        month = :month,
                        year = :year,
                        eamil = :email,
                        role_id = :role_id
                    WHERE id = :id";
        }

        $conn = Connection::getInstance();
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception("Failed to save user");
        }
        else {
            $rowCount = $stmt->rowCount();
            if ($rowCount !== 1) {
                throw new Exception("Error saving user");
            }
            if ($this->id === NULL) {
                $this->id = $conn->lastInsertId('users');
            }
        }
    }

    public function delete() {
        if (empty($this->id)) {
            throw new Exception("Unsaved user cannot be deleted");
        }
        $params = array(
            'id' => $this->id
        );
        $sql = 'DELETE FROM users WHERE id = :id';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception("Failed to delete user");
        }
        else {
            $rowCount = $stmt->rowCount();
            if ($rowCount !== 1) {
                throw new Exception("Error deleting user");
            }
        }
    }

    public static function all() {
        $sql = 'SELECT * FROM users';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception("Failed to retrieve users");
        }
        else {
            $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
            return $users;
        }
    }

    public static function find($id) {
        $params = array(
            'id' => $id
        );
        $sql = 'SELECT * FROM users WHERE id = :id';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception("Failed to retrieve user");
        }
        else {
            $user = $stmt->fetchObject('User');
            return $user;
        }
    }

    public static function findByUsername($username) {
        $params = array(
            'username' => $username
        );
        $sql = 'SELECT * FROM users WHERE username = :username';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception("Failed to retrieve user");
        }
        else {
            $user = $stmt->fetchObject('User');
            return $user;
        }
    }
}
?>
