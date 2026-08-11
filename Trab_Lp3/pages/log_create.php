<?php
session_start();

if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

require_once __DIR__ . '/../repository/UsuarioRepository.php';
require_once __DIR__ . '/../phpmailer/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/SMTP.php';
require_once __DIR__ . '/../phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$erro = '';
$sucesso = '';
$nomeForm = $_POST['nome'] ?? '';
$emailForm = $_POST['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    // Validações
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
        
        $usuarioExistente = $repo->buscarPorEmail($email);
        
        if ($usuarioExistente) {
            $erro = 'Este e-mail já está cadastrado. <a href="login.php">Faça login</a>';
        } else {
            try {
    $senhaHash = hash('sha256', $senha);

    $codigo = random_int(100000, 999999);

    $_SESSION['cadastro_temp'] = [
    'nome' => $nome,
    'email' => $email,
    'senha' => $senhaHash
];

$_SESSION['codigo_confirmacao'] = $codigo;

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'heitorlamimleone2@gmail.com';
    $mail->Password = 'zclo vwof xrzs iypf';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('heitorlamimleone2@gmail.com', 'CRUDspect');
    $mail->addAddress($email, $nome);

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    $mail->Subject = 'Código de confirmação';

    $mail->Body = "
        <h2>Confirmação de Cadastro</h2>
        <p>Olá, {$nome}, falta pouco para concluir seu cadastro.</p>
        <p>Seu código de confirmação é:</p>
        <h1>{$codigo}</h1>
    ";

    $mail->send();

    header('Location: confirmar_codigo.php');
    exit;

} catch (Exception $e) {

    unset($_SESSION['cadastro_temp']);
    unset($_SESSION['codigo_confirmacao']);

    $erro = 'Não foi possível enviar o e-mail de confirmação.';
}

} catch (Exception $e) {
    $erro = 'Erro ao criar conta: ' . $e->getMessage();
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
  <title>Login — CRUDspect</title>
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

    <?php if ($sucesso !== ''): ?>
        <div class="alert alert-sucesso"><?= $sucesso ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nome">Nome completo</label>
            <input
                type="text"
                id="nome"
                name="nome"
                placeholder="Seu nome completo"
                value="<?= htmlspecialchars($nomeForm) ?>"
                required
            />
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="seu@email.com"
                value="<?= htmlspecialchars($emailForm) ?>"
                required
            />
        </div>

        <div class="form-group">
            <label for="senha">Criar Senha</label>
            <input
                type="password"
                id="senha"
                name="senha"
                placeholder="•••••••• (mínimo 6 caracteres)"
                required
            />
        </div>

        <div class="form-group">
            <label for="confirmar_senha">Confirmar Senha</label>
            <input
                type="password"
                id="confirmar_senha"
                name="confirmar_senha"
                placeholder="Digite a senha novamente"
                required
            />
        </div>

        <button type="submit" class="btn btn-primary btn-full">Criar conta</button>
    </form>

    <div class="login-divider">
        <span>ou</span>
    </div>

    <a href="login.php" class="btn btn-secondary btn-full">
        Já tenho uma conta → Fazer login
    </a>
</div>

</body>
</html>
