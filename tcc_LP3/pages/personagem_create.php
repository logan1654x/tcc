<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/PersonagemRepository.php';
require_once __DIR__ . '/../repository/Habilidades.php';

$repo = new PersonagemRepository();

$erro = '';
$nome = '';
$classe = '';

$classes = ['Lutador(a)', 'Atirador(a)', 'Medico(a)', 'Escudeiro(a)'];

// Mapa de imagens por classe (caminho relativo à raiz do projeto)
$imagensClasses = [
    'Lutador(a)'   => 'assets/classes/Lutador.png',
    'Atirador(a)'  => 'assets/classes/Atirador.png',
    'Medico(a)'    => 'assets/classes/Medico.png',
    'Escudeiro(a)' => 'assets/classes/Escudeiro.png',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome'] ?? '');
    $classe   = trim($_POST['classe'] ?? '');

    // Pega o caminho da imagem baseado na classe escolhida
    $caminhoImagem = $imagensClasses[$classe] ?? null;

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
  <form method="POST" action="personagem_create.php" id="formPersonagem">
    
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