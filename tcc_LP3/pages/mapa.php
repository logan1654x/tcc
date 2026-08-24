<?php
require_once __DIR__ . '/../includes/auth.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mapa</title>

    <link rel="stylesheet" href="../assets/style.css">
</head>

<body class="mapa_pag">

    <div id="mapa">

        <!-- ==============================
             IMAGEM DO MAPA
        =============================== -->

        <img
            id="imagem_mapa"
            src="../bosses/mapa/fundo.png"
            alt="Mapa"
        >


        <!-- ==============================
             CAMINHOS
        =============================== -->

        <div id="caminhos"></div>


        <!-- ==============================
             PONTOS
        =============================== -->

        <div id="pontos"></div>


        <!-- ==============================
             PERSONAGEM
        =============================== -->

        <div id="personagem_mapa">

            <img
                src="../bosses/mapa/perso.png"
                alt="Personagem"
            >

        </div>


        <!-- ==============================
             INFORMAÇÃO DO BIOMA
        =============================== -->

        <div id="info_bioma">

            <h2 id="nome_bioma"></h2>

            <p id="texto_bioma"></p>

            <div id="botoes_bioma">

                <button id="entrar_bioma">
                    ENTRAR
                </button>

                <button id="fechar_bioma">
                    VOLTAR
                </button>

            </div>

        </div>


        <!-- ==============================
             COORDENADAS
        =============================== -->

        <div id="coordenadas">

            Altura:
            <span id="altura_atual">0</span>

            |

            Distância:
            <span id="distancia_atual">0</span>

        </div>


        <!-- ==============================
             INSTRUÇÕES
        =============================== -->

        <div id="controles_mapa">

            <p>W ↑</p>
            <p>A ← &nbsp; D →</p>
            <p>S ↓</p>

        </div>

    </div>


<script>

/* =========================================================
   CONFIGURAÇÃO DO JOGADOR
========================================================= */

let jogador = {

    altura: 0,

    distancia: 0

};


/* =========================================================
   BIOMAS DO MAPA
=========================================================

   altura = eixo vertical

   distancia = eixo horizontal

========================================================= */

const biomas = [

    {
        nome: "Caatinga",

        altura: 1,

        distancia: 2,

        descricao: "Você chegou à Caatinga.",

        local: "caatinga"
    },

    {
        nome: "Amazônia",

        altura: 2,

        distancia: -2,

        descricao: "Você chegou à Amazônia.",

        local: "amazonia"
    },

    {
        nome: "Mata Atlântica",

        altura: 1,

        distancia: -2,

        descricao: "Você chegou à Mata Atlântica.",

        local: "mata_atlantica"
    }

];


/* =========================================================
   PONTOS DO CAMINHO
=========================================================

   Estes são os pontos onde o jogador pode ficar.

   Você pode adicionar/remover pontos depois.

========================================================= */

const pontos = [

    {
        altura: 0,
        distancia: 0,

        x: 49,
        y: 95
    },

    {
        altura: 1,
        distancia: 0,

        x: 49,
        y: 40
    },

    {
        altura: 1,
        distancia: 1,

        x: 55,
        y: 45
    },

    {
        altura: 1,
        distancia: 2,

        x: 80,
        y: 18
    },

    {
        altura: 1,
        distancia: -2,

        x: 19,
        y: 18
    },

    {
        altura: 1,
        distancia: -1,

        x: 43,
        y: 45
    },

    {
        altura: 2,
        distancia: 0,

        x: 49,
        y: 18
    }

];


/* =========================================================
   REFERÊNCIAS HTML
========================================================= */

const personagemElemento =
    document.getElementById("personagem_mapa");

const coordenadasElemento =
    document.getElementById("coordenadas");

const alturaElemento =
    document.getElementById("altura_atual");

const distanciaElemento =
    document.getElementById("distancia_atual");

const infoBioma =
    document.getElementById("info_bioma");

const nomeBioma =
    document.getElementById("nome_bioma");

const textoBioma =
    document.getElementById("texto_bioma");

const botaoEntrar =
    document.getElementById("entrar_bioma");

const botaoFechar =
    document.getElementById("fechar_bioma");


/* =========================================================
   TAMANHO DA GRADE
========================================================= */

const distanciaEntrePontos = 12;


/* =========================================================
   CONVERTER COORDENADA EM POSIÇÃO NA TELA
========================================================= */

function obterPosicaoTela(altura, distancia) {

    const ponto = pontos.find(function(p) {

        return (
            p.altura === altura &&
            p.distancia === distancia
        );

    });

    if (!ponto) {
        return {
            x: 50,
            y: 50
        };
    }

    return {
        x: ponto.x,
        y: ponto.y
    };
}

/* =========================================================
   ATUALIZAR POSIÇÃO DO PERSONAGEM
========================================================= */

function atualizarPersonagem() {

    const posicao =
        obterPosicaoTela(
            jogador.altura,
            jogador.distancia
        );


    personagemElemento.style.left =
        posicao.x + "%";


    personagemElemento.style.top =
        posicao.y + "%";


    alturaElemento.textContent =
        jogador.altura;


    distanciaElemento.textContent =
        jogador.distancia;


    verificarBioma();

}


/* =========================================================
   VERIFICAR SE ESTÁ EM UM BIOMA
========================================================= */

function verificarBioma() {

    const biomaEncontrado =
        biomas.find(function(bioma) {

            return (
                bioma.altura === jogador.altura &&
                bioma.distancia === jogador.distancia
            );

        });


    if (!biomaEncontrado) {

        infoBioma.style.display = "none";

        return;

    }


    mostrarBioma(biomaEncontrado);

}


/* =========================================================
   MOSTRAR INFORMAÇÃO DO BIOMA
========================================================= */

function mostrarBioma(bioma) {

    nomeBioma.textContent =
        bioma.nome;


    textoBioma.textContent =
        bioma.descricao;


    infoBioma.style.display =
        "block";


    /*
       Guarda o bioma atual
       para o botão ENTRAR.
    */

    infoBioma.dataset.local =
        bioma.local;

}


/* =========================================================
   ENTRAR NO BIOMA
========================================================= */

botaoEntrar.addEventListener("click", function() {

    const local =
        infoBioma.dataset.local;


    if (!local) {

        return;

    }


    /*
       Pega a partida que já existe.
    */

    let partida =
        JSON.parse(
            localStorage.getItem("partida")
        );


    /*
       Se ainda não existir uma partida,
       cria uma.
    */

    if (!partida) {

        partida = {};

    }


    /*
       Define o bioma escolhido.
    */

    partida.local =
        local;


    /*
       Salva novamente.
    */

    localStorage.setItem(
        "partida",
        JSON.stringify(partida)
    );


    /*
       Vai para a batalha.
    */

    window.location.href =
        "partida.php";

});


/* =========================================================
   BOTÃO VOLTAR
========================================================= */

botaoFechar.addEventListener("click", function() {

    infoBioma.style.display =
        "none";

});


/* =========================================================
   VERIFICAR SE UMA COORDENADA EXISTE
========================================================= */

function existePonto(altura, distancia) {

    return pontos.some(function(ponto) {

        return (
            ponto.altura === altura &&
            ponto.distancia === distancia
        );

    });

}


/* =========================================================
   MOVIMENTAÇÃO
========================================================= */

document.addEventListener("keydown", function(event) {

    /*
       Se o jogador estiver vendo
       a janela do bioma, não movimenta.
    */

    if (
        infoBioma.style.display === "block"
    ) {

        return;

    }


    let novaAltura =
        jogador.altura;

    let novaDistancia =
        jogador.distancia;


    /* ==========================
       W = CIMA
    ========================== */

    if (
        event.key === "w" ||
        event.key === "W" ||
        event.key === "ArrowUp"
    ) {

        novaAltura++;

    }


    /* ==========================
       S = BAIXO
    ========================== */

    else if (
        event.key === "s" ||
        event.key === "S" ||
        event.key === "ArrowDown"
    ) {

        novaAltura--;

    }


    /* ==========================
       A = ESQUERDA
    ========================== */

    else if (
        event.key === "a" ||
        event.key === "A" ||
        event.key === "ArrowLeft"
    ) {

        novaDistancia--;

    }


    /* ==========================
       D = DIREITA
    ========================== */

    else if (
        event.key === "d" ||
        event.key === "D" ||
        event.key === "ArrowRight"
    ) {

        novaDistancia++;

    }


    else {

        return;

    }


    /*
       Verifica se existe um ponto
       nessa direção.
    */

    if (
        existePonto(
            novaAltura,
            novaDistancia
        )
    ) {

        jogador.altura =
            novaAltura;

        jogador.distancia =
            novaDistancia;


        atualizarPersonagem();

    }

});


/* =========================================================
   CRIAR OS PONTOS VISUAIS
========================================================= */

function criarPontos() {

    const container =
        document.getElementById("pontos");


    pontos.forEach(function(ponto) {

        const elemento =
            document.createElement("div");


        elemento.className =
            "ponto_mapa";


        const posicao =
            obterPosicaoTela(
                ponto.altura,
                ponto.distancia
            );


        elemento.style.left =
            posicao.x + "%";


        elemento.style.top =
            posicao.y + "%";


        container.appendChild(
            elemento
        );

    });

}


/* =========================================================
   INICIAR MAPA
========================================================= */

criarPontos();

atualizarPersonagem();

</script>

</body>

</html>