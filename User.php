<?php
class User {
    private $conn;
    private $table = "Users"; // Ensure this matches your table name

    public $UserID;
    public $Username;
    public $Email;
    public $PasswordHash;
    public $PermissionLevelID;
    public $ProfilePicture;
    public $Bio;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Sign Up
    public function signup() {
        $query = "INSERT INTO Users (Username, Email, PasswordHash, PermissionLevelID) VALUES (:username, :email, :passwordHash, :permissionLevelID)";
        $stmt = $this->conn->prepare($query);

        $this->Username = htmlspecialchars(strip_tags($this->Username));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->PasswordHash = password_hash($this->PasswordHash, PASSWORD_BCRYPT);
        $this->PermissionLevelID = 1; // Default to Normal User

        $stmt->bindParam(":username", $this->Username);
        $stmt->bindParam(":email", $this->Email);
        $stmt->bindParam(":passwordHash", $this->PasswordHash);
        $stmt->bindParam(":permissionLevelID", $this->PermissionLevelID);

        return $stmt->execute();
    }

    // Login
    public function login() {
        $query = "SELECT UserID, Username, PasswordHash, PermissionLevelID, ProfilePicture, Bio FROM Users WHERE Username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $this->Username = htmlspecialchars(strip_tags($this->Username));
        $stmt->bindParam(":username", $this->Username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($this->PasswordHash, $row['PasswordHash'])) {
            $this->UserID = $row['UserID'];
            $this->PermissionLevelID = $row['PermissionLevelID'];
            $this->ProfilePicture = $row['ProfilePicture'];
            $this->Bio = $row['Bio'];
            return true;
        }
        return false;
    }

    // Update Profile Picture
    public function updateProfilePicture($profilePicture) {
        $query = "UPDATE Users SET ProfilePicture = :profilePicture WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":profilePicture", $profilePicture);
        $stmt->bindParam(":userID", $this->UserID);

        return $stmt->execute();
    }

    // Check if username already exists
    public function usernameExists() {
        $query = "SELECT Username FROM Users WHERE Username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->Username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Check if email already exists
    public function emailExists() {
        $query = "SELECT Email FROM Users WHERE Email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->Email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>
