<?php
session_start();

require_once __DIR__ . '/../repository/UsuarioRepository.php';
if(!isset($_SESSION['cadastro_temp']) || !isset ($_SESSION['codigo_confirmacao']))
    {
        header('Location: log_create.php');
        exit;
    }
$erro = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $codigoDigitado = trim($_POST['codigo']);

    if((string)$codigoDigitado === (string)$_SESSION['codigo_confirmacao']){
        echo "Código Correto";
        $dados = $_SESSION['cadastro_temp'];
        $repository = new UsuarioRepository();
        $repository->inserir(
            $dados['nome'],
            $dados['email'],
            $dados['senha']
        );
        unset($_SESSION['cadastro_temp']);
        unset($_SESSION['codigo_confirmacao']);

        header('Location: login.php');
        exit;
    } else{
        $erro = 'Código incorreto';
    }
}?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Cadastro</title>

    <link rel="stylesheet" href="../assets/style.css">
    <link rel="icon" type="image/x-icon" href="../assets/gate.ico">
</head>
<body class="login-body">

<div class="login-card">

    <div class="login-logo">CRUDspect</div>

    <h1 class="login-title">Confirmar Cadastro</h1>

    <p style="text-align:center; margin-bottom:20px;">
        Digite o código enviado para o seu e-mail.
    </p>

    <?php if ($erro !== ''): ?>
        <div class="alert alert-erro">
            <?= $erro ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label for="codigo">Código de confirmação</label>

            <input
                type="text"
                id="codigo"
                name="codigo"
                placeholder="Digite o código recebido"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary btn-full">
            Confirmar Cadastro
        </button>

    </form>

</div>

</body>
</html>