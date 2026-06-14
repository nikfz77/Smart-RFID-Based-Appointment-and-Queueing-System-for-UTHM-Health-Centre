<?php
// session_ping.php — keeps session alive, never forces logout
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['last_activity'] = time();
http_response_code(204);
?>