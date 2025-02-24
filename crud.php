<?php
include 'db_connection.php';  

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        createUser();
        break;
    case 'read':
        getUsers();
        break;
    case 'delete':
        deleteUser();
        break;
    case 'update':
        updateUser();
        break;
    default:
        echo json_encode(["message" => "Invalid action"]);
}

// Function to create a new user
function createUser() {
    global $conn;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $query = "INSERT INTO users (name, email, phone) VALUES (:name, :email, :phone)";

    try {
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        echo json_encode(["message" => "User added successfully"]);
    } catch (PDOException $e) {
        echo json_encode(["message" => "Error adding user: " . $e->getMessage()]);
    }
}

// Function to get all users
function getUsers() {
    global $conn;
    $query = "SELECT * FROM users";

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    } catch (PDOException $e) {
        echo json_encode(["message" => "Error fetching users: " . $e->getMessage()]);
    }
}

// Function to delete a user
function deleteUser() {
    global $conn;
    $id = $_POST['id'];

    $query = "DELETE FROM users WHERE id = :id";

    try {
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(["message" => "User deleted successfully"]);
    } catch (PDOException $e) {
        echo json_encode(["message" => "Error deleting user: " . $e->getMessage()]);
    }
}

// Function to update a user
function updateUser() {
    global $conn;
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $query = "UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :id";

    try {
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        echo json_encode(["message" => "User updated successfully"]);
    } catch (PDOException $e) {
        echo json_encode(["message" => "Error updating user: " . $e->getMessage()]);
    }
}
?>
