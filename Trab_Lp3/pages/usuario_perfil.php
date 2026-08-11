<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/UsuarioRepository.php';

$repo = new UsuarioRepository();
$usuario = $repo->buscarPorId($_SESSION['usuario_id']);

if (!$usuario) {
    header('Location: index.php');
    exit;
}

$erro = '';
$sucesso = '';
$aba_ativa = $_GET['aba'] ?? 'perfil';

// Processar atualização do perfil (biografia e foto)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'perfil') {
    $biografia = trim($_POST['biografia'] ?? '');
    $foto_path = $usuario->getFotoPerfil();
    
    // VERIFICAR SE DEVE REMOVER A FOTO
    if (isset($_POST['remover_imagem']) && $_POST['remover_imagem'] === '1') {
        if ($usuario->getFotoPerfil() && file_exists(__DIR__ . '/../' . $usuario->getFotoPerfil())) {
            unlink(__DIR__ . '/../' . $usuario->getFotoPerfil());
        }
        $foto_path = null;
        $sucesso = "Foto removida com sucesso!";
    }
    
    // Processar upload da nova imagem (só se não tiver removido)
    if (!isset($_POST['remover_imagem']) && isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $tipo_arquivo = $_FILES['foto_perfil']['type'];
        $extensoes_permitidas = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        
        if (in_array($tipo_arquivo, $extensoes_permitidas)) {
            if ($_FILES['foto_perfil']['size'] > 5 * 1024 * 1024) {
                $erro = "Imagem muito grande! Máximo 5MB.";
            } else {
                $upload_dir = __DIR__ . '/../uploads/perfil/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                if ($usuario->getFotoPerfil() && file_exists(__DIR__ . '/../' . $usuario->getFotoPerfil())) {
                    unlink(__DIR__ . '/../' . $usuario->getFotoPerfil());
                }
                
                $extensao = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
                $nome_arquivo = 'user_' . $usuario->getId() . '_' . uniqid() . '.' . $extensao;
                $caminho_relativo = 'uploads/perfil/' . $nome_arquivo;
                $caminho_absoluto = $upload_dir . $nome_arquivo;
                
                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_absoluto)) {
                    $foto_path = $caminho_relativo;
                    $sucesso = "Foto atualizada com sucesso!";
                } else {
                    $erro = "Erro ao salvar a foto.";
                }
            }
        } else {
            $erro = "Formato de imagem não permitido. Use JPG, PNG, GIF ou WEBP.";
        }
    }
    
    if (empty($erro)) {
        try {
            $usuario->setBiografia($biografia);
            $usuario->setFotoPerfil($foto_path);
            $repo->atualizarPerfil($usuario);
            if (empty($sucesso) && empty($erro)) {
                $sucesso = "Perfil atualizado com sucesso!";
            }
        } catch (Exception $e) {
            $erro = $e->getMessage();
        }
    }
}

// Processar atualização de dados da conta (nome e email)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'conta') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($nome) || empty($email)) {
        $erro = "Nome e email são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido.";
    } else {
        try {
            $usuarioExistente = $repo->buscarPorEmail($email);
            if ($usuarioExistente && $usuarioExistente->getId() !== $usuario->getId()) {
                $erro = "Este email já está cadastrado por outro usuário.";
            } else {
                $usuario->setNome($nome);
                $usuario->setEmail($email);
                $repo->atualizarDadosBasicos($usuario->getId(), $nome, $email);
                $_SESSION['usuario_nome'] = $nome;
                $sucesso = "Dados atualizados com sucesso!";
            }
        } catch (Exception $e) {
            $erro = $e->getMessage();
        }
    }
}

// Processar atualização de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'senha') {
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    if (empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)) {
        $erro = "Todos os campos de senha são obrigatórios.";
    } elseif ($nova_senha !== $confirmar_senha) {
        $erro = "A nova senha e a confirmação não coincidem.";
    } elseif (strlen($nova_senha) < 6) {
        $erro = "A nova senha deve ter pelo menos 6 caracteres.";
    } else {
        $senha_hash = hash('sha256', $senha_atual);
        if ($senha_hash !== $usuario->getSenha()) {
            $erro = "Senha atual incorreta.";
        } else {
            try {
                $nova_senha_hash = hash('sha256', $nova_senha);
                $repo->atualizarSenha($usuario->getId(), $nova_senha_hash);
                $sucesso = "Senha alterada com sucesso!";
            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Meu Perfil</h2>
  <a href="index.php" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro !== ''): ?>
  <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<?php if ($sucesso !== ''): ?>
  <div class="alert alert-sucesso"><?= htmlspecialchars($sucesso) ?></div>
<?php endif; ?>

<div class="profile-tabs">
  <a href="?aba=perfil" class="tab <?= $aba_ativa === 'perfil' ? 'tab-ativa' : '' ?>">Perfil</a>
  <a href="?aba=conta" class="tab <?= $aba_ativa === 'conta' ? 'tab-ativa' : '' ?>">Dados da Conta</a>
  <a href="?aba=senha" class="tab <?= $aba_ativa === 'senha' ? 'tab-ativa' : '' ?>">Alterar Senha</a>
</div>

<?php if ($aba_ativa === 'perfil'): ?>
<div class="form-card">
  <form method="POST" action="usuario_perfil.php?aba=perfil" enctype="multipart/form-data" id="formPerfil">
    <input type="hidden" name="acao" value="perfil">
    
    <div class="perfil-foto-container">
      <label>Foto atual</label>
      <div class="preview-container">
        <div class="preview-image-wrapper">
          <?php if ($usuario->getFotoPerfil() && file_exists(__DIR__ . '/../' . $usuario->getFotoPerfil())): ?>
            <img id="currentImagePreview" src="/Trab_Lp3/<?= $usuario->getFotoPerfil() ?>" class="foto-preview-round">
          <?php else: ?>
            <div id="currentImagePreview" class="foto-preview-placeholder-round"></div>
          <?php endif; ?>
        </div>
      </div>
      
      <div class="form-group">
        <label>
          <input type="checkbox" name="remover_imagem" value="1" id="removerImagemCheckbox" 
            <?php if (!$usuario->getFotoPerfil() || !file_exists(__DIR__ . '/../' . $usuario->getFotoPerfil())) echo 'disabled'; ?>>
          Remover foto atual
        </label>
      </div>
    </div>

    <div class="form-group">
      <label for="foto_perfil">Nova foto (opcional)</label>
      <div id="novaFotoPreview" class="preview-new" style="display: none;">
        <img id="newImagePreview" class="foto-preview-round">
      </div>
      <input type="file" id="foto_perfil" name="foto_perfil" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this)">
      <small>Formatos: JPG, PNG, GIF, WEBP. Máximo: 5MB</small>
    </div>

    <div class="form-group">
      <label for="biografia">Biografia</label>
      <textarea id="biografia" name="biografia" rows="5"><?= htmlspecialchars($usuario->getBiografia() ?? '') ?></textarea>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar Perfil</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>
  </form>
</div>

<script>
function previewImage(input) {
    const previewDiv = document.getElementById('novaFotoPreview');
    const previewImg = document.getElementById('newImagePreview');
    const removerCheckbox = document.getElementById('removerImagemCheckbox');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewDiv.style.display = 'block';
            if (removerCheckbox) {
                removerCheckbox.checked = false;
            }
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        previewDiv.style.display = 'none';
        previewImg.src = '';
    }
}

const removerCheckbox = document.getElementById('removerImagemCheckbox');
if (removerCheckbox) {
    removerCheckbox.addEventListener('change', function() {
        const novaPreview = document.getElementById('novaFotoPreview');
        const fileInput = document.getElementById('foto_perfil');
        const currentPreview = document.getElementById('currentImagePreview');
        
        if (this.checked) {
            novaPreview.style.display = 'none';
            fileInput.value = '';
            if (currentPreview) {
                currentPreview.style.opacity = '0.5';
            }
        } else {
            if (currentPreview) {
                currentPreview.style.opacity = '1';
            }
        }
    });
}
</script>
<?php endif; ?>

<?php if ($aba_ativa === 'conta'): ?>
<div class="form-card">
  <form method="POST" action="usuario_perfil.php?aba=conta">
    <input type="hidden" name="acao" value="conta">
    
    <div class="form-group">
      <label for="nome">Nome de usuário</label>
      <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario->getNome()) ?>" required>
    </div>

    <div class="form-group">
      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>" required>
    </div>

    <div class="form-group">
      <label>Data de cadastro</label>
      <input type="text" value="<?= date('d/m/Y H:i', strtotime($usuario->getCriadoEm())) ?>" disabled>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Salvar Dados</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>
  </form>
</div>
<?php endif; ?>

<?php if ($aba_ativa === 'senha'): ?>
<div class="form-card">
  <form method="POST" action="usuario_perfil.php?aba=senha">
    <input type="hidden" name="acao" value="senha">
    
    <div class="form-group">
      <label for="senha_atual">Senha atual</label>
      <input type="password" id="senha_atual" name="senha_atual" required>
    </div>

    <div class="form-group">
      <label for="nova_senha">Nova senha</label>
      <input type="password" id="nova_senha" name="nova_senha" required>
      <small>Mínimo de 6 caracteres</small>
    </div>

    <div class="form-group">
      <label for="confirmar_senha">Confirmar nova senha</label>
      <input type="password" id="confirmar_senha" name="confirmar_senha" required>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Alterar Senha</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>
  </form>
</div>
<?php endif; ?>

