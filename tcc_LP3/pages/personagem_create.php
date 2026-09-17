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
        $habilidadeClasse =
        $habilidadesRepo->buscarPorOrigem($classe);
        $habilidadeAspecto =
        $habilidadesRepo->buscarPorOrigem($aspecto);
        $todashabilidade = array_merge($habilidadeClasse, $habilidadeAspecto);
        shuffle($todashabilidade);

        $habilidadesSelecionadas = array_slice($todashabilidade, 0, 3);
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
    </div>


    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Cadastrar personagem</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>

  </form>
</div>





<?php require_once __DIR__ . '/../includes/footer.php'; ?>