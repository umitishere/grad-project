<?php

class User {

    private $pdo, $sqlData;

    public function __construct($pdo, $username) {

        $this->pdo = $pdo;

        $query = $this->pdo->prepare("SELECT * FROM users WHERE username = :un");        
        $query->bindParam(":un", $username);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);

    }

    public function getUsername() {
        return $this->sqlData["username"] ?? "";
    }

    public function getUserID() {
        return $this->sqlData["id"];
    }

    public function getProfilePicture() {
        return $this->sqlData["profile_photo"];
    }

}

?>
