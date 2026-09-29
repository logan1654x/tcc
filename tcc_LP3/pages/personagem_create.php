<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/PersonagemRepository.php';
require_once __DIR__ . '/../repository/Habilidades.php';

$repo = new PersonagemRepository();

$erro = '';
$nome = '';
$classe = '';

$classes = ['Lutador(a)', 'Atirador(a)', 'Medico(a)', 'Escudeiro(a)'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome'] ?? '');
    $classe   = trim($_POST['classe'] ?? '');
    $caminhoImagem = null;
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $tipo_arquivo = $_FILES['imagem']['type'];
        $extensoes_permitidas = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        
        if (in_array($tipo_arquivo, $extensoes_permitidas)) {
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $nome_arquivo = uniqid() . '.' . $extensao;
            $caminho_relativo = 'uploads/' . $nome_arquivo;
            $caminho_absoluto = __DIR__ . '/../uploads/' . $nome_arquivo;
            
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_absoluto)) {
                $caminhoImagem = $caminho_relativo;
            } else {
                $erro = "Erro ao salvar a imagem.";
            }
        } else {
            $erro = "Formato de imagem não permitido. Use JPG, PNG, GIF ou WEBP.";
        }
    }

    try {
        $personagem = Personagem::novo($nome, $classe, $_SESSION['usuario_id'], $caminhoImagem);
        $repo->salvar($personagem);
        $habilidadesRepo = new Habilidades();

        $habilidadeClasse = $habilidadesRepo->buscarPorOrigem($classe);
        
        shuffle($habilidadeClasse);
        
        $habilidadesSelecionadas = array_slice($habilidadeClasse, 0, 3);
        foreach ($habilidadesSelecionadas as $habilidade) {
            $habilidadesRepo->associarAoPersonagem(
                $personagem->getId(),
                $habilidade['id']
            );
        }
        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $erro = $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Novo personagem</h2>
  <a href="index.php" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro !== ''): ?>
  <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="form-card">
  <form method="POST" action="personagem_create.php" enctype="multipart/form-data" id="formPersonagem">
    
    <div class="form-group">
      <label for="nome">Nome do personagem</label>
      <input type="text" id="nome" name="nome" placeholder="Ex: Flint" value="<?= htmlspecialchars($nome) ?>" required />
    </div>

    <div class="form-group">
      <label for="classe">Classe</label>
      <select id="classe" name="classe" required>
        <option value="">Selecione a Classe...</option>
        <?php foreach ($classes as $t): ?>
          <option value="<?= $t ?>" <?= ($classe === $t) ? 'selected' : '' ?>><?= $t ?></option>
        <?php endforeach; ?>
      </select>

      <!-- PREVIEW DA CLASSE -->
      <div id="preview-classe" class="preview-classe" style="display: none;">
        <img id="imagem-classe" src="" alt="Classe selecionada">
        <span id="nome-classe-preview"></span>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Cadastrar personagem</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>

  </form>
</div>

<script>
// ===================== PREVIEW DA CLASSE =====================

// Mapa de imagens por classe (nomes exatos como aparecem no <option>)
const imagensClasses = {
    'Lutador(a)':   '../assets/classes/Lutador.png',
    'Atirador(a)':  '../assets/classes/Atirador.png',
    'Medico(a)':    '../assets/classes/Medico.png',
    'Escudeiro(a)': '../assets/classes/Escudeiro.png'
};

const selectClasse       = document.getElementById('classe');
const previewClasse      = document.getElementById('preview-classe');
const imagemClasse       = document.getElementById('imagem-classe');
const nomeClassePreview  = document.getElementById('nome-classe-preview');

if (selectClasse) {
    // Se já houver uma classe pré-selecionada (ex: após erro no formulário), mostra o preview
    if (selectClasse.value !== '') {
        mostrarPreviewClasse(selectClasse.value);
    }

    selectClasse.addEventListener('change', function() {
        if (this.value === '') {
            previewClasse.style.display = 'none';
        } else {
            mostrarPreviewClasse(this.value);
        }
    });
}

function mostrarPreviewClasse(classeSelecionada) {
    const caminho = imagensClasses[classeSelecionada];

    if (caminho) {
        imagemClasse.src = caminho;
        imagemClasse.alt = classeSelecionada;
        nomeClassePreview.textContent = classeSelecionada;
        previewClasse.style.display = 'flex';
    } else {
        previewClasse.style.display = 'none';
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>