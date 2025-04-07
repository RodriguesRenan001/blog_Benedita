<?php
// Inicia a sessão com configurações seguras
function secureSessionStart() {
    $session_name = 'blog_escolar_session';
    $secure = true; // Apenas em HTTPS
    $httponly = true; // Impede acesso via JavaScript
    
    ini_set('session.use_only_cookies', 1);
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params(
        $cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly
    );
    session_name($session_name);
    session_start();
    session_regenerate_id(true);
}

// Valida o RA escolar (formato: AAANNNNN onde A=ano e N=número)
function validarRA($ra) {
    return preg_match('/^20[0-9]{2}[0-9]{4}$/', $ra);
}

// Previne XSS
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Verifica força da senha
function isSenhaForte($senha) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $senha);
}

// Gera token CSRF
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verifica token CSRF
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>