<?php
session_start();

if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../repository/UsuarioRepository.php';

$erro = '';
$nomeForm = $_POST['nome'] ?? '';
$emailForm = $_POST['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    // Validações básicas
    if ($nome === '' || $email === '' || $senha === '') {
        $erro = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Digite um e-mail válido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmarSenha) {
        $erro = 'As senhas não coincidem.';
    } else {
        $repo = new UsuarioRepository();
        
        // Verifica se o e-mail já existe
        $usuarioExistente = $repo->buscarPorEmail($email);
        
        if ($usuarioExistente) {
            $erro = 'Este e-mail já está cadastrado. <a href="login.php">Faça login</a>';
        } else {
            try {
                // Cria o hash da senha
                $senhaHash = hash('sha256', $senha);
                
                // Insere diretamente no banco de dados (sem e-mail de confirmação)
                $repo->inserir($nome, $email, $senhaHash);
                
                // Redireciona para o login
                header('Location: login.php');
                exit;
                
            } catch (Exception $e) {
                $erro = 'Erro ao criar conta no banco de dados.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Criar Conta — CRUDspect</title>
  <link rel="stylesheet" href="../assets/style.css" />
  <link rel="icon" type="image/x-icon" href="../assets/gate.ico">
</head>
<body class="login-body">

<div class="login-card">
    <div class="login-logo">CRUDspect</div>
    <h1 class="login-title">Criar uma conta</h1>

    <?php if ($erro !== ''): ?>
        <div class="alert alert-erro"><?= $erro ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome" placeholder="Seu nome completo" value="<?= htmlspecialchars($nomeForm) ?>" required />
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="seu@email.com" value="<?= htmlspecialchars($emailForm) ?>" required />
        </div>

        <div class="form-group">
            <label for="senha">Criar Senha</label>
            <input type="password" id="senha" name="senha" placeholder="•••••••• (mínimo 6 caracteres)" required />
        </div>

        <div class="form-group">
            <label for="confirmar_senha">Confirmar Senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Digite a senha novamente" required />
        </div>

        <button type="submit" class="btn btn-primary btn-full">Criar conta</button>
    </form>

    <div class="login-divider">
        <span>ou</span>
    </div>

    <a href="login.php" class="btn btn-secondary btn-full">Já tenho uma conta → Fazer login</a>
</div>

</body>
</html>