<?php
// Local XAMPP defaults. Change these values to match your MySQL setup.
$host = '127.0.0.1'; $db = 'goldcart'; $user = 'root'; $pass = '';
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
function csrf_token(): string {
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
  return $_SESSION['csrf'];
}
function verify_csrf(): void {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sent = $_POST['csrf'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
      http_response_code(403); exit('Invalid request token. Go back and try again.');
    }
  }
}
function require_login(): void {
  if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
}
