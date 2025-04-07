<?php
require_once __DIR__ . '/../config/security.php';
secureSessionStart();

if (!isset($_SESSION['csrf_token'])) {
    generateCSRFToken();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Blog Educacional - Escola Estadual">
    
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Blog Escolar'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/estilo.css">
    <?php if (isset($cssFiles)): ?>
        <?php foreach ($cssFiles as $cssFile): ?>
            <link rel="stylesheet" href="/assets/css/<?php echo $cssFile; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- JavaScript -->
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <?php include __DIR__ . '/alerts.php'; ?>
    
    <main class="container">