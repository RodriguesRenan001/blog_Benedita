<?php
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../config/database.php';
requerLogin();

$db = new Database();
$conn = $db->getConnection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCSRFToken($_POST['csrf_token'])) {
    $titulo = sanitizeInput($_POST['titulo']);
    $conteudo = sanitizeInput($_POST['conteudo']);
    $usuario_id = $_SESSION['user_id'];
    
    // Validação básica
    if (empty($titulo) || empty($conteudo)) {
        $error = 'Todos os campos são obrigatórios.';
    } elseif (strlen($titulo) > 100) {
        $error = 'O título não pode ter mais de 100 caracteres.';
    } else {
        try {
            $query = "INSERT INTO posts (titulo, conteudo, usuario_id, criado_em) 
                      VALUES (:titulo, :conteudo, :usuario_id, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':conteudo', $conteudo);
            $stmt->bindParam(':usuario_id', $usuario_id);
            
            if ($stmt->execute()) {
                $success = 'Postagem criada com sucesso!';
                
                // Limpa os campos do formulário
                $titulo = $conteudo = '';
            } else {
                $error = 'Ocorreu um erro ao criar a postagem.';
            }
        } catch (PDOException $e) {
            error_log("Erro ao criar postagem: " . $e->getMessage());
            $error = 'Ocorreu um erro ao processar sua solicitação.';
        }
    }
}

$pageTitle = 'Criar Nova Postagem';
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-plus-circle"></i> Nova Postagem</h2>
                </div>
                
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/posts/criar.php">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" id="titulo" name="titulo" class="form-control" 
                                   value="<?php echo isset($titulo) ? $titulo : ''; ?>" required maxlength="100">
                        </div>
                        
                        <div class="form-group">
                            <label for="conteudo">Conteúdo</label>
                            <textarea id="conteudo" name="conteudo" class="form-control" rows="10" required><?php 
                                echo isset($conteudo) ? $conteudo : ''; 
                            ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Publicar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>