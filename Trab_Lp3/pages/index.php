<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/PersonagemRepository.php';

$repo = new PersonagemRepository();
$personagens = $repo->listarPorUsuario($_SESSION['usuario_id']);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Meus Personagens god tier</h2>
  <a href="personagem_create.php" class="btn btn-primary">+ Novo personagem</a>
  
   <?php if (!empty($personagens)): ?>
    <a href="partida_create.php" class="btn btn-success">
      Iniciar Partida
    </a>
  <?php endif; ?>
</div>

<!-- Barra de pesquisa -->
<div class="search-bar-container">
  <div class="search-wrapper">
    <input 
      type="text" 
      id="searchInput" 
      placeholder="Pesquisar por nome do personagem..." 
      autocomplete="off"
    >
    <button id="clearSearch" class="clear-search" style="display: none;">✕</button>
  </div>
  <div id="searchResultCount" class="search-result-count"></div>
</div>

<?php if (empty($personagens)): ?>
  <div class="empty-state">
    <p>Você ainda não cadastrou nenhum personagem.</p>
    <a href="personagem_create.php" class="btn btn-primary">Cadastrar agora</a>
  </div>
<?php else: ?>
  <div class="table-wrapper">
    <table class="data-table" id="personagemTable">
      <thead>
        <tr>
          <th>Foto</th>
          <th></th>
          <th>Nome</th>
          <th>Classe</th>
          <th>Aspecto</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <?php foreach ($personagens as $personagem): ?>
          <tr data-nome="<?= strtolower(htmlspecialchars($personagem->getNome())) ?>">
            <td style="text-align: center; vertical-align: middle;">
              <?php if ($personagem->getCaminhoImagem() && file_exists(__DIR__ . '/../' . $personagem->getCaminhoImagem())): ?>
                <img src="/Trab_Lp3/<?= $personagem->getCaminhoImagem() ?>" 
                     alt="<?= htmlspecialchars($personagem->getNome()) ?>"
                     class="personagem-avatar"
                     style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #1a1a1a;">
              <?php else: ?>
                <div class="personagem-avatar-placeholder" style="width: 45px; height: 45px; background: #d3d3d3; border: 2px solid #1a1a1a; display: inline-flex; align-items: center; justify-content: center;">
                  
                </div>
              <?php endif; ?>
            </td>
            <td><?= '' ?></td>
            <td><strong><?= htmlspecialchars($personagem->getNome()) ?></strong></td>
            <td><span class="badge"><?= htmlspecialchars($personagem->getClasse()) ?></span></td>
            <td><span class="badge"><?= htmlspecialchars($personagem->getAspecto()) ?></span></td>
            <td class="acoes">
              <a href="personagem_edit.php?id=<?= $personagem->getId() ?>" class="btn btn-sm btn-editar">Editar</a>
              <a href="personagem_delete.php?id=<?= $personagem->getId() ?>" class="btn btn-sm btn-excluir">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<script>
// Pesquisa em tempo real
const searchInput = document.getElementById('searchInput');
const tableBody = document.getElementById('tableBody');
const resultCount = document.getElementById('searchResultCount');
const clearBtn = document.getElementById('clearSearch');

function filterTable() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    const rows = tableBody.getElementsByTagName('tr');
    let visibleCount = 0;
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const nomeCell = row.getElementsByTagName('td')[2];
        
        if (nomeCell) {
            const nome = nomeCell.textContent || nomeCell.innerText;
            
            if (searchTerm === '' || nome.toLowerCase().indexOf(searchTerm) > -1) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }
    }
    
    // Mostrar/esconder botão de limpar
    if (searchTerm !== '') {
        clearBtn.style.display = 'flex';
        resultCount.innerHTML = ` ${visibleCount} personagem(ns) encontrado(s)`;
    } else {
        clearBtn.style.display = 'none';
        resultCount.innerHTML = ` Total: ${rows.length} personagem(ns)`;
    }
    
    // Mostrar mensagem se nenhum resultado
    if (visibleCount === 0 && searchTerm !== '') {
        resultCount.innerHTML = ` Nenhum personagem encontrado para "${searchTerm}"`;
    }
}

// Limpar pesquisa
function clearSearch() {
    searchInput.value = '';
    filterTable();
    searchInput.focus();
}

// Eventos
searchInput.addEventListener('input', filterTable);
clearBtn.addEventListener('click', clearSearch);

// Inicializar contador
filterTable();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>