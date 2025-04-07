<nav class="navbar">
    <div class="navbar-brand">
        <a href="/index.php" class="logo">
            <img src="/assets/images/logo_escola.png" alt="Logo da Escola">
            <span>Blog Escolar</span>
        </a>
        <button class="navbar-toggle" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <div class="navbar-links">
        <ul>
            <li><a href="/index.php"><i class="fas fa-home"></i> Início</a></li>
            <li><a href="/posts/listar.php"><i class="fas fa-book"></i> Postagens</a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="/perfil.php"><i class="fas fa-user"></i> Meu Perfil</a></li>
                <li><a href="/posts/criar.php"><i class="fas fa-plus"></i> Nova Postagem</a></li>
                
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <li><a href="/admin/dashboard.php"><i class="fas fa-cog"></i> Administração</a></li>
                <?php endif; ?>
                
                <li><a href="/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            <?php else: ?>
                <li><a href="/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="/cadastro.php"><i class="fas fa-user-plus"></i> Cadastro</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>