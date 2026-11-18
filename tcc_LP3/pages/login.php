<?php
session_start();

// Se já estiver logado, vai direto para a página inicial
if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../repository/UsuarioRepository.php';

$erro = '';
$sucesso = '';
$emailFormulario = '';

// 1. Verifica se o usuário acabou de criar uma conta (vindo do log_create.php)
if (isset($_SESSION['sucesso_cadastro'])) {
    $sucesso = $_SESSION['sucesso_cadastro'];
    unset($_SESSION['sucesso_cadastro']);
}

// 2. Verifica se veio um erro de login (após redirecionamento)
if (isset($_SESSION['erro_login'])) {
    $erro = $_SESSION['erro_login'];
    $emailFormulario = $_SESSION['email_form'] ?? '';
    unset($_SESSION['erro_login'], $_SESSION['email_form']);
}

// 3. Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $_SESSION['erro_login'] = 'Preencha todos os campos.';
        $_SESSION['email_form'] = $email;
        header('Location: login.php');
        exit;
    }

    $repo = new UsuarioRepository();
    $usuario = $repo->buscarPorEmail($email);

    // A senha salva no banco usa SHA-256 (igual ao log_create.php)
    if ($usuario && hash('sha256', $senha) === $usuario->getSenha()) {
        // Login com sucesso! Salva na sessão
        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['usuario_nome'] = $usuario->getNome();
        
        header('Location: index.php');
        exit;
    } else {
        // Falha no login
        $_SESSION['erro_login'] = 'E-mail ou senha inválidos.';
        $_SESSION['email_form'] = $email;
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — CRUDspect</title>
  <link rel="stylesheet" href="../assets/style.css" />
  <link rel="icon" type="image/x-icon" href="../assets/gate.ico">
</head>
<body class="login-body">

<div class="login-card">
  <div class="login-logo">CRUDspect</div>
  <h1 class="login-title">Entrar no sistema</h1>

  <!-- Exibe erro se houver -->
  <?php if ($erro !== ''): ?>
    <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <!-- Exibe mensagem de sucesso (quando a conta foi criada) -->
  <?php if ($sucesso !== ''): ?>
    <div class="alert alert-sucesso"><?= htmlspecialchars($sucesso) ?></div>
  <?php endif; ?>

  <form method="POST" action="login.php">
    <div class="form-group">
      <label for="email">E-mail</label>
      <input
        type="email"
        id="email"
        name="email"
        placeholder="seu@email.com"
        value="<?= htmlspecialchars($emailFormulario) ?>"
        required
      />
    </div>

    <div class="form-group">
      <label for="senha">Senha</label>
      <input
        type="password"
        id="senha"
        name="senha"
        placeholder="••••••••"
        required
      />
    </div>

    <button type="submit" class="btn btn-primary btn-full">Entrar</button>
    
    <div class="login-divider">
        <span>ou</span>
    </div>

    <a href="log_create.php" class="btn btn-secondary btn-full">Criar nova conta</a>
  </form>
</div>

</body>
</html>