<?php
// Enable error display for debugging
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
// Handle GET: Fetch all users
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $result = $conn->query("SELECT id, name, email, role, is_admin, is_artist FROM users");

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode(["users" => $users]);
    exit;
}

// Read input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
$name      = trim($data["name"] ?? "");
$email     = trim($data["email"] ?? "");
$password  = $data["password"] ?? "";
$role      = $data["role"] ?? "user";
$is_admin  = $data["is_admin"] ?? 0;
$is_artist = $data["is_artist"] ?? 0;

if (!$name || !$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Name, email, and password are required"]);
    exit;
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["error" => "Email already registered"]);
    exit;
}

// Hash the password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role, is_admin, is_artist) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssii", $name, $email, $hashed, $role, $is_admin, $is_artist);

if ($stmt->execute()) {
    echo json_encode([
        "message" => "🎉 User registered successfully!",
        "user_id" => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Insert failed: " . $stmt->error]);
}
?>
