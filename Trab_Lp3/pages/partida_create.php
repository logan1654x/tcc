<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/PersonagemRepository.php';


$repo = new PersonagemRepository();
$personagens = $repo->listarPorUsuario($_SESSION['usuario_id']);

require_once __DIR__ . '/../includes/header.php';

?>
<script>
    let partida = {
    personagens: [],
    dificuldade: null,
    local: null
};
</script>

<div class="search-bar-container2">
  <div class="search-wrapper2">
    <span class="search-icon2">🔍</span>

    <input 
      type="text" 
      id="searchInput2"
      placeholder="Pesquisar personagem..."
    >

    <button id="clearSearch2" class="clear-search2">
      ✕
    </button>
  </div>

  <div id="searchResultCount2" class="search-result-count2"></div>
</div>

<div class="table-wrapper2">

<table class="data-table2" id="personagemTable2">

  <thead>
    <tr>
      <th>Selecionar</th>
      <th>Foto</th>
      <th>Nome</th>
      <th>Classe</th>
    </tr>
  </thead>

  <tbody>

    <?php foreach ($personagens as $personagem): ?>

      <tr>

        <td>
          <input 
            type="checkbox"
            class="select-personagem"
            data-id="<?= $personagem->getId() ?>"
          >
        </td>

        <td>
          <img 
            src="/Trab_Lp3/<?= $personagem->getCaminhoImagem() ?>"
            class="personagem-avatar2"
          >
        </td>

        <td class="nome-personagem">
          <?= htmlspecialchars($personagem->getNome()) ?>
        </td>

        <td>
          <?= htmlspecialchars($personagem->getClasse()) ?>
        </td>

      </tr>

    <?php endforeach; ?>

  </tbody>

</table>

</div>

<div class="selected-area2">

    <h2>PERSONAGENS SELECIONADOS</h2>

    <div 
        id="selectedCharacters2"
        class="selected-grid2"
    >

    </div>

</div>

<script>

/* ===================================
   ELEMENTOS
=================================== */

const searchInput2 =
    document.getElementById('searchInput2');

const clearSearch2 =
    document.getElementById('clearSearch2');

const resultCount2 =
    document.getElementById('searchResultCount2');

const rows2 =
    document.querySelectorAll(
        '#personagemTable2 tbody tr'
    );

const selectedContainer =
    document.getElementById(
        'selectedCharacters2'
    );

const checkboxes =
    document.querySelectorAll(
        '.select-personagem'
    );



/* ===================================
   PESQUISA
=================================== */

searchInput2.addEventListener('input', () => {

    const termo =
        searchInput2.value.toLowerCase();

    let visibleCount = 0;

    rows2.forEach((row) => {

        const nome =
            row.querySelector('.nome-personagem')
            .innerText
            .toLowerCase();

        if(nome.includes(termo)){

            row.style.display = '';

            visibleCount++;

        } else {

            row.style.display = 'none';

        }

    });

    resultCount2.innerText =
        visibleCount + ' personagem(ns) encontrado(s)';
        
    

    /* BOTÃO LIMPAR */

    if(termo.length > 0){

        clearSearch2.style.display = 'flex';

    } else {

        clearSearch2.style.display = 'none';

        resultCount2.innerText = '';

    }

});



/* ===================================
   LIMPAR PESQUISA
=================================== */

clearSearch2.addEventListener('click', () => {

    searchInput2.value = '';

    rows2.forEach((row) => {

        row.style.display = '';

    });

    resultCount2.innerText = '';

    clearSearch2.style.display = 'none';

});



/* ===================================
   SELECIONAR PERSONAGENS
=================================== */

const maxSelecionados = 3;

checkboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', (e) => {

        const selecionados =
            document.querySelectorAll('.select-personagem:checked');

        // SE tentou marcar mais de 3
        if (selecionados.length > maxSelecionados) {
            e.target.checked = false;
            alert('Você só pode selecionar até 3 personagens.');
            return;
        }

        updateSelectedCharacters();

        <!-- mostra o botao que via pra segunda parte so se tiver 3 personagen  -->
        const btn = document.querySelector('.but-escolher-dif');

        if (selecionados.length === 3) {
            btn.style.display = '';
        } else {
            btn.style.display = 'none';
        }
    });
});



function updateSelectedCharacters() {

    selectedContainer.innerHTML = '';

    partida.personagens = [];

    const selectedRows =
        document.querySelectorAll('.select-personagem:checked');

    selectedRows.forEach((checkbox) => {

        const id = checkbox.getAttribute('data-id');

        const row = checkbox.closest('tr');

        const nome =
            row.querySelector('.nome-personagem').innerText;

        const imagem =
            row.querySelector('img').src;

        partida.personagens.push(id);

        const card = document.createElement('div');

        card.classList.add('selected-character-card2');

        card.innerHTML = `
            <img src="${imagem}">
            <span>${nome}</span>
        `;

        selectedContainer.appendChild(card);
    });
 console.log(partida);
}

</script>

<!-- esconder tabela e barra de pesquisa (segunda parte da crição de partida) -->
  

<div class="but-escolher-dif" style="display: none;">
    <button onclick="segunda_parte()" class="btn-escolher-dif" >
        Próximo
    </button>
</div>
<script>
    function segunda_parte() {
       document.querySelector('.search-bar-container2').style.display = 'none';
       document.querySelector('.table-wrapper2').style.display = 'none';
       document.querySelector('.but-escolher-dif').style.display = 'none';
         document.querySelector('.escolha-dificuldade').style.display = 'block';

    }
</script>
<!-- escolher dificuldade -->

<div class="escolha-dificuldade" style="display: none;">
    <h2>ESCOLHA A DIFICULDADE</h2>
    <div class="botoes-dificuldade">
        <button onclick="selecionarDificuldade('Fácil')" class="btn-dificuldade">Fácil</button>
        <button onclick="selecionarDificuldade('Médio')" class="btn-dificuldade">Médio</button>
        <button onclick="selecionarDificuldade('Difícil')" class="btn-dificuldade">Difícil</button>
    </div>
    <h2>ESCOLHA O LOCAL DA PARTIDA</h2>
    <div class="Local-partida">
        <button onclick="selecionarLocal('deserto')" class="btn-local">Deserto</button>
        <button onclick="selecionarLocal('floresta')" class="btn-local">Floresta</button>
        <button onclick="selecionarLocal('montanha')" class="btn-local">Montanha</button>
    </div>
    <p style="color: white; font-size: 18px; margin-top: 20px;" class="mostrar_pred_part"> dificuldade: <span id="dificuldade-selecionada">Nenhuma</span> local: <span id="local-selecionado">Nenhum</span></p>
    <button onclick="createPartida()" class="btn-create" style="display: none;">criar partida</button>
</div>
<script>
    let verlocal = 0;
    let verdif = 0;
function selecionarDificuldade(dificuldade) {
    verdif = 1;
    document.getElementById('dificuldade-selecionada').innerText = dificuldade;
    partida.dificuldade = dificuldade;
    if(verlocal==1&&verdif==1){
        document.querySelector('.btn-create').style.display = 'block';
    }
}
function selecionarLocal(local) {
    verlocal = 1;
    document.getElementById('local-selecionado').innerText = local;
    partida.local = local;
    if(verlocal==1&&verdif==1) { 
        document.querySelector('.btn-create').style.display = 'block';
    }
}
function createPartida() {

    console.log("OBJETO:", partida);
    
    localStorage.setItem("partida", JSON.stringify(partida));

    fetch("partida_salvar.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(partida)
    })
    .then(response => response.json())
    .then(data => {

        console.log(data);

        alert("Partida salva!");

        window.location.href = "partida.php";
    })
    .catch(error => {
        console.error("Erro:", error);
    });
}

</script>