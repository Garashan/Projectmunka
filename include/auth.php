<?php

function isLoggedIn(): bool
{
    return isset($_SESSION["user_id"]);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header("Location: /fiok");
        exit;
    }
}
function logout(): void
{
    // Session változók törlése
    $_SESSION = [];

    // Session cookie törlése
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax'
        ]);
    }

    // Session megszüntetése
    session_destroy();
}