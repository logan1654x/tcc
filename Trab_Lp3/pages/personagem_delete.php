<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/PersonagemRepository.php';

$repo = new PersonagemRepository();

$id = 0;
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
}

$personagem = null;
if ($id > 0) {
    $personagem = $repo->buscarPorId($id);
}

// Pokémon não encontrado ou não pertence ao usuário logado
if ($personagem === null || $personagem->getUsuarioId() !== $_SESSION['usuario_id']) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $repo->excluir($personagem->getId());
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Excluir personagem</h2>
  <a href="index.php" class="btn btn-ghost">← Voltar</a>
</div>

<div class="confirm-card">
  <h3>Você tem certeza?</h3>
  <p>
    Você está prestes a excluir o personagem
    <strong><?= htmlspecialchars($personagem->getNome()) ?></strong>
    (<?= htmlspecialchars($personagem->getClasse()) ?>, Lv. <?= $personagem->getAspecto() ?>).
    Esta ação não pode ser desfeita.
  </p>

  <form method="POST" action="personagem_delete.php?id=<?= $personagem->getId() ?>">
    <div class="form-actions">
      <button type="submit" class="btn btn-excluir">Sim, excluir</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
