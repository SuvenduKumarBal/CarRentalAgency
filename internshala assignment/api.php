<?php
header("Content-Type: application/json");
$response = [];
require_once 'config.php'; 

class Database {
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
           
            error_log("Database Connection Error: " . $e->getMessage(), 0);
            die("Oops! Something went wrong.");
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}


function connectToDatabase() {
$servername = "your_servername"; 
$username = "your_username";     
$password = "your_password";     
$dbname = "car_rental_system";   
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
}

function registerUser($name, $email, $password) {
    $conn = connectToDatabase();
    if (!$conn) {
        return false;
    }

    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function loginUser($email, $password) {
    $conn = connectToDatabase();
    if (!$conn) {
        return false;
    }

    try {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return false;
    }
}

function addNewCar($vehicleModel, $vehicleNumber, $seatingCapacity, $rentPerDay) {
    $conn = connectToDatabase();
    if (!$conn) {
        return false;
    }

    try {
        $sql = "INSERT INTO cars (vehicle_model, vehicle_number, seating_capacity, rent_per_day) VALUES (:model, :number, :capacity, :rent)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':model', $vehicleModel);
        $stmt->bindParam(':number', $vehicleNumber);
        $stmt->bindParam(':capacity', $seatingCapacity);
        $stmt->bindParam(':rent', $rentPerDay);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function rentCar($selectedCar, $startDate, $numberOfDays) {
    $conn = connectToDatabase();
    if (!$conn) {
        return false;
    }

    try {
        $sql = "INSERT INTO rentals (car_id, start_date, num_days) VALUES (:carId, :startDate, :numDays)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':carId', $selectedCar);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':numDays', $numberOfDays);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"];

    if ($action === "register") {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        $registrationSuccess = registerUser($name, $email, $password);

        if ($registrationSuccess) {
            $response["success"] = true;
            $response["message"] = "Registration successful!";
        } else {
            $response["success"] = false;
            $response["message"] = "Registration failed";
        }
    } elseif ($action === "login") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $loginSuccess = loginUser($email, $password);

        if ($loginSuccess) {
            $response["success"] = true;
            $response["message"] = "Login successful!";
        } else {
            $response["success"] = false;
            $response["message"] = "Login failed";
        }
    } elseif ($action === "add_car") {
        $vehicleModel = $_POST["vehicleModel"];
        $vehicleNumber = $_POST["vehicleNumber"];
        $seatingCapacity = $_POST["seatingCapacity"];
        $rentPerDay = $_POST["rentPerDay"];

        $addCarSuccess = addNewCar($vehicleModel, $vehicleNumber, $seatingCapacity, $rentPerDay);

        if ($addCarSuccess) {
            $response["success"] = true;
            $response["message"] = "Car added successfully!";
        } else {
            $response["success"] = false;
            $response["message"] = "Car addition failed";
        }
    } elseif ($action === "rent_car") {
        $selectedCar = $_POST["selectedCar"];
        $startDate = $_POST["startDate"];
        $numberOfDays = $_POST["numberOfDays"];

        $rentCarSuccess = rentCar($selectedCar, $startDate, $numberOfDays);

        if ($rentCarSuccess) {
            $response["success"] = true;
            $response["message"] = "Car rental request submitted successfully!";
        } else {
            $response["success"] = false;
            $response["message"] = "Car rental request failed";
        }
    } else {
        $response["success"] = false;
        $response["message"] = "Invalid action";
    }
} else {
    $response["success"] = false;
    $response["message"] = "Invalid request method";
}

echo json_encode($response);
?>
