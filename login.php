<?php
require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/config/database.php';

$db = new Database();
$conn = $db->getConnection();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'])) {
    $ra = sanitizeInput($_POST['ra']);
    $senha = $_POST['senha'];
    
    if (validarRA($ra)) {
        $query = "SELECT id, nome, senha, tipo FROM usuarios WHERE ra = :ra AND ativo = 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':ra', $ra);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($senha, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nome'] = $user['nome'];
                $_SESSION['user_ra'] = $ra;
                $_SESSION['user_role'] = $user['tipo'];
                $_SESSION['last_activity'] = time();
                
                // Registrar login
                $ip = $_SERVER['REMOTE_ADDR'];
                $query = "INSERT INTO logs_acesso (usuario_id, ip, acao) VALUES (:user_id, :ip, 'login')";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':user_id', $user['id']);
                $stmt->bindParam(':ip', $ip);
                $stmt->execute();
                
                header('Location: /index.php');
                exit();
            } else {
                $error = 'RA ou senha incorretos.';
            }
        } else {
            $error = 'RA ou senha incorretos.';
        }
    } else {
        $error = 'Formato de RA inválido.';
    }
}

$pageTitle = 'Login - Blog Escolar';
$cssFiles = ['auth.css'];
include __DIR__ . '/includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h1><i class="fas fa-sign-in-alt"></i> Login</h1>
        
        <form method="POST" action="/login.php" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="ra"><i class="fas fa-id-card"></i> Registro Acadêmico (RA)</label>
                <input type="text" id="ra" name="ra" required placeholder="Digite seu RA" 
                       pattern="20[0-9]{6}" title="O RA deve ter 8 dígitos, começando com o ano de ingresso">
            </div>
            
            <div class="form-group">
                <label for="senha"><i class="fas fa-lock"></i> Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Digite sua senha">
                <small><a href="/recuperar_senha.php">Esqueceu a senha?</a></small>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Não tem uma conta? <a href="/cadastro.php">Cadastre-se</a></p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>