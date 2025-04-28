<?php
// Enable error display
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

// Load .env config
$env = parse_ini_file(__DIR__ . '/../.env');

$host = $env['DB_HOST'];
$db   = $env['DB_NAME'];
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];

// Connect to MySQL
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Read input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
$email    = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Email and password are required"]);
    exit;
}

// Find user by email
$stmt = $conn->prepare("SELECT id, name, email, password, role, is_admin, is_artist FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user["password"])) {
        echo json_encode([
            "message" => "✅ Login successful!",
            "user" => [
                "id"        => $user["id"],
                "name"      => $user["name"],
                "email"     => $user["email"],
                "role"      => $user["role"],
                "is_admin"  => $user["is_admin"],
                "is_artist" => $user["is_artist"]
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Incorrect password"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
}
?>
