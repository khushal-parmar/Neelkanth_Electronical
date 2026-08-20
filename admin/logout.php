<?php
session_start();

// 1. સેશનના બધા જ વેરીએબલ્સ સાફ કરો
$_SESSION = array();

// 2. Cookie માંથી સેશન આઈડી ડિલીટ કરો
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. સેશન નાશ કરો
session_destroy();

// 4. સીધા Login પેજ પર રીડાયરેક્ટ કરો
header("Location: login.php");
exit();
?>