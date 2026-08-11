<?php
require_once __DIR__ . '/../includes/auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<audio id="bgMusic" loop autoplay>
    <source src="../assets/musicas_boss/Showtime_Imp.mp3" type="audio/mpeg">
</audio>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partida RPG</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../bosses/bosseshab.js"></script>
</head>
<body class="partida_pag">

<div id="turno_container">Vez do jogador</div>

<div id="ataque_boss_container">
    <div id="ataque_boss_caixa">
        <button id="fechar_ataque">X</button>
        <h2 id="nome_ataque"></h2>
        <p id="resultado_ataque"></p>
    </div>
</div>

<div id="info"></div>
<div id="boss_container"></div>

<div id="habilidades-container">
    <ul id="habilidades-list"></ul>
</div>

<script>
    // ===================== VARIÁVEIS GLOBAIS =====================
    let jogadorSelecionado = -1;          // índice do personagem selecionado para escolher habilidade
    let modoSelecaoAlvo = false;          // true quando estamos escolhendo alvo da cura
    let curaPendente = null;              // guarda {nome, quantidade} da cura pendente
    let ataqueSelecionado = false;
    let danoAtaque = 0;

    // ===================== BATALHA =====================
    let batalha = {
        turno: "jogador",
        bossControlado: false,
        bonusAtaque: 0,
        bonusCura: 0,
        reducaoAtaqueBoss: 0,

        jogadores: [
            { id: 1, nome: "", vida: 100, vidaMax: 100 },
            { id: 2, nome: "", vida: 100, vidaMax: 100 },
            { id: 3, nome: "", vida: 100, vidaMax: 100 }
        ],
        boss: {
            id: "boss",
            nome: "Boss",
            vida: 0,
            vidaMax: 0
        },
        jaAgui: [false, false, false]   // controle de quem já agiu no turno atual
    };

    // ===================== FUNÇÕES AUXILIARES =====================
    function criarBarraVida(atual, max, tipo) {
        let pct = (atual / max) * 100;
        return `
            <div class="vida_texto">Vida: ${atual}/${max}</div>
            <div class="barra">
                <div class="vida ${tipo}" style="width:${pct}%"></div>
            </div>
        `;
    }

    function atualizarVidaJogadores() {
        const personagens = document.querySelectorAll(".personagem");
        batalha.jogadores.forEach((jogador, index) => {
            let personagem = personagens[index];
            if (!personagem) return;
            let barra = personagem.querySelector(".vida.player");
            let texto = personagem.querySelector(".vida_texto");
            let pct = (jogador.vida / jogador.vidaMax) * 100;
            if (pct < 0) pct = 0;
            barra.style.width = pct + "%";
            texto.textContent = `Vida: ${jogador.vida}/${jogador.vidaMax}`;
            if (jogador.vida <= 0) {
                personagem.style.opacity = "0.3";
                personagem.style.pointerEvents = "none";
                personagem.classList.add("morto");
                batalha.jaAgui[index] = true; // morto conta como já agiu para não travar
            }
        });
        // Atualiza visual de quem já agiu
        personagens.forEach((div, i) => {
            if (batalha.jaAgui[i] && batalha.jogadores[i].vida > 0) {
                div.style.opacity = "0.6";
                div.style.border = "2px solid #888";
            } else if (batalha.jogadores[i].vida > 0) {
                div.style.opacity = "1";
                div.style.border = "";
            }
        });
    }

    function atualizarVidaBoss() {
        const barraVida = document.querySelector(".vida.boss");
        const textoVida = document.querySelector(".boss_vida .vida_texto");
        if (!barraVida || !textoVida) return;
        if (batalha.boss.vida > 0) {
            let pct = (batalha.boss.vida / batalha.boss.vidaMax) * 100;
            barraVida.style.width = pct + "%";
            textoVida.textContent = `Vida: ${batalha.boss.vida}/${batalha.boss.vidaMax}`;
        } else {
            barraVida.style.width = "0%";
            textoVida.textContent = `Vida: 0/${batalha.boss.vidaMax}`;
            mostrar_resultado("VITÓRIA", "O boss foi derrotado!");
            setTimeout(() => window.location.href = "../pages/index.php", 3000);
        }
    }

    function atualizar_contai() {
        const caixa = document.getElementById("turno_container");
        if (batalha.turno === "jogador") {
            let agiram = batalha.jaAgui.filter(a => a === true).length;
            caixa.innerText = `Vez do jogador (${agiram}/3 agiram)`;
        } else {
            caixa.innerText = "Vez do boss";
        }
    }

    function turnoboss() {
        batalha.turno = "boss";
        batalha.jaAgui = [false, false, false]; // reseta para o próximo turno
        atualizar_contai();
        ataque_do_boss();
    }

    function ataque_do_boss() {
        if(batalha.bossControlado){
            alert("O boss está sob controle e perdeu o turno!");

            batalha.bossControlado = false;

            batalha.turno = "jogador";
            batalha.jaAgui = [false, false, false];
            atualizarVidaJogadores();
            atualizar_contai();
            return;

}
        let partida = JSON.parse(localStorage.getItem("partida"));
        let bossAtual;
        if (partida.local == "deserto") bossAtual = bosses.deserto;
        else if (partida.local == "floresta") bossAtual = bosses.floresta;
        else bossAtual = bosses.montanha;

        let habilidade = bossAtual.habilidades[Math.floor(Math.random() * bossAtual.habilidades.length)];
        let resultado = "";

        if (habilidade.tipo == "ataque_area") {
            batalha.jogadores.forEach(jogador => {
                let danoFinal = habilidade.dano - batalha.reducaoAtaqueBoss;

                if(danoFinal < 0){
                danoFinal = 0;
                }

                jogador.vida -= danoFinal;

                resultado += `${jogador.nome} perdeu ${danoFinal} de vida<br>`;
            });
        } else if (habilidade.tipo == "ataque") {
            let alvo = Math.floor(Math.random() * 3);
            batalha.jogadores[alvo].vida -= habilidade.dano;
            resultado += `${batalha.jogadores[alvo].nome} perdeu ${habilidade.dano} de vida`;
        }

        mostrar_ataque_boss(habilidade.nome, resultado);
        atualizarVidaJogadores();
        verificarDerrota();

        // Depois do ataque do boss, volta o turno para o jogador
        batalha.turno = "jogador";
        batalha.jaAgui = [false, false, false];
        atualizar_contai();
        // Limpa seleção de personagem
        jogadorSelecionado = -1;
        document.querySelectorAll('.personagem').forEach(el => el.style.border = '');
        document.getElementById('habilidades-list').innerHTML = '';
    }

    function mostrar_ataque_boss(nome, resultado) {
        document.getElementById("nome_ataque").innerText = "Boss usou: " + nome;
        document.getElementById("resultado_ataque").innerHTML = resultado;
        document.getElementById("ataque_boss_container").style.display = "block";
    }

    function verificarDerrota() {
        let mortos = batalha.jogadores.filter(j => j.vida <= 0);
        if (mortos.length == 3) {
            mostrar_resultado("DERROTA", "Todos os jogadores foram derrotados!");
            setTimeout(() => window.location.href = "../pages/index.php", 3000);
        }
    }

    function mostrar_resultado(titulo, texto) {
        document.getElementById("nome_ataque").innerText = titulo;
        document.getElementById("resultado_ataque").innerHTML = texto;
        document.getElementById("ataque_boss_container").style.display = "block";
    }

    // ===================== FUNÇÕES DE CURA E SELEÇÃO DE ALVO =====================
    function iniciarSelecaoAlvo(nomeHabilidade, quantidadeCura) {
        modoSelecaoAlvo = true;
        curaPendente = { nome: nomeHabilidade, cura: quantidadeCura };

        const container = document.getElementById('habilidades-list');
        container.innerHTML = `
            <li style="background:#2b7a3e;color:white;text-align:center;padding:10px;list-style:none;border-radius:8px;">
                ${nomeHabilidade} - Clique em um personagem para curar
            </li>
            <li style="text-align:center;color:#e8e507;padding:5px;list-style:none;">
                Clique em qualquer membro da equipe (vivo)
            </li>
            <li style="text-align:center;color:#ff6b6b;padding:5px;cursor:pointer;list-style:none;" onclick="cancelarSelecao()">
                Cancelar
            </li>
        `;

        const divs = document.querySelectorAll('.personagem');
        divs.forEach((div, i) => {
            const jogador = batalha.jogadores[i];
            if (jogador && jogador.vida > 0 && !batalha.jaAgui[i]) {
                div.style.border = '3px solid #e8e507';
                div.style.cursor = 'pointer';
                div.style.boxShadow = '0 0 15px rgba(232,229,7,0.3)';
                div.dataset.alvo = 'true';
            } else {
                div.style.opacity = '0.4';
                div.style.cursor = 'not-allowed';
                div.dataset.alvo = 'false';
            }
        });
    }

    function cancelarSelecao() {
        modoSelecaoAlvo = false;
        curaPendente = null;
        const divs = document.querySelectorAll('.personagem');
        divs.forEach(div => {
            div.style.border = '';
            div.style.cursor = 'pointer';
            div.style.boxShadow = '';
            div.style.opacity = '1';
            div.dataset.alvo = 'false';
        });
        document.getElementById('habilidades-list').innerHTML = '';
    }

    function aplicarBuff(nome){

        batalha.bonusAtaque += 10;

        if (batalha.bonusAtaque > 50) {
            batalha.bonusAtaque = 50;
        }

        batalha.jaAgui[jogadorSelecionado] = true;

        atualizarVidaJogadores();
        atualizar_contai();

        alert(nome + " aumentou o ataque da equipe!");

        if(batalha.jaAgui.every(v => v === true)){
        setTimeout(() => turnoboss(), 500);
        }
}

    function aplicarDebuff(nome){

        batalha.reducaoAtaqueBoss += 10;
        if (batalha.reducaoAtaqueBoss > 50) {
            batalha.reducaoAtaqueBoss = 50;
        }

        batalha.jaAgui[jogadorSelecionado] = true;

        atualizarVidaJogadores();
        atualizar_contai();

        alert(nome + " reduziu o ataque do boss!");

        if(batalha.jaAgui.every(v => v === true)){
            setTimeout(() => turnoboss(), 500);
        }
}

    function aplicarPassiva(nome){

        batalha.bonusCura += 10;
        if (batalha.bonusCura > 50) {
        batalha.bonusCura = 50;
        }

        alert(nome + " aumentou a cura recebida!");

}

    function aplicarControle(nome){

        let chance = Math.random();

        if(chance <= 0.5){

        batalha.bossControlado = true;

        alert(nome + " funcionou! O boss perdeu o próximo turno.");
        } 
        else {

        alert(nome + " falhou! O boss resistiu ao controle.");
        }

        batalha.jaAgui[jogadorSelecionado] = true;

        atualizarVidaJogadores();
        atualizar_contai();

        if(batalha.jaAgui.every(v => v === true)){
        setTimeout(() => turnoboss(), 500);
        }

}

    function aplicarCuraNoAlvo(index, quantidade) {
        const jogador = batalha.jogadores[index];
        if (!jogador || jogador.vida <= 0) {
            alert("Este personagem está morto!");
            return false;
        }
        const curaReal = Math.min(quantidade + batalha.bonusCura, jogador.vidaMax - jogador.vida);
        jogador.vida += curaReal;

        atualizarVidaJogadores();

        const divs = document.querySelectorAll('.personagem');
        if (divs[index]) {
            divs[index].style.border = '3px solid #a8e6cf';
            setTimeout(() => divs[index].style.border = '', 1000);
        }

        // Limpa modo de seleção
        modoSelecaoAlvo = false;
        const nomeCura = curaPendente.nome;
        curaPendente = null;

        const divsAll = document.querySelectorAll('.personagem');
        divsAll.forEach(div => {
            div.style.border = '';
            div.style.cursor = 'pointer';
            div.style.boxShadow = '';
            div.style.opacity = '1';
            div.dataset.alvo = 'false';
        });

        const container = document.getElementById('habilidades-list');
        container.innerHTML = `
            <li style="background:#2b7a3e;color:#a8e6cf;text-align:center;padding:10px;list-style:none;border-radius:8px;">
                ${nomeCura} aplicada com sucesso em ${jogador.nome}!
            </li>
        `;
        setTimeout(() => container.innerHTML = '', 2000);

        // Marca o personagem que usou a cura como já agiu
        if (jogadorSelecionado !== -1) {
            batalha.jaAgui[jogadorSelecionado] = true;
            atualizarVidaJogadores();
            atualizar_contai();
        }

        // Verifica se todos já agiram
        if (batalha.jaAgui.every(v => v === true)) {
            setTimeout(() => turnoboss(), 500);
        }
        return true;
    }

    // ===================== FUNÇÕES DE ATAQUE =====================
    function ativarAtaqueBoss(dano) {
        ataqueSelecionado = true;
        danoAtaque = dano;
        const boss = document.getElementById("boss_container");
        boss.classList.add("ataque_ativo");
        document.getElementById('habilidades-list').innerHTML = '<li style="text-align:center;color:#e8e507;font-weight:bold;list-style:none;">Clique no BOSS para atacar!</li>';
    }


    // ===================== FUNÇÃO PRINCIPAL DE HABILIDADE =====================
    function ver_ação(nome, tipo, dano, cura) {
        if (batalha.turno !== "jogador") {
            alert("Aguarde o turno do boss!");
            return;
        }

        if (jogadorSelecionado === -1) {
            alert("Selecione um personagem primeiro!");
            return;
        }

        // Verifica se o personagem já agiu neste turno
        if (batalha.jaAgui[jogadorSelecionado]) {
            alert("Este personagem já agiu neste turno!");
            return;
        }

        // ---- CURA ----
        if (tipo === "Cura" || tipo === "cura") {
            if (!cura || cura <= 0) {
                alert("Esta habilidade não tem cura!");
                return;
            }
            iniciarSelecaoAlvo(nome, cura);
            return;
        }

        // ---- ATAQUE ----
        if (tipo === "Ataque" || tipo === "ataque") {
            if (batalha.boss.vida <= 0) {
                alert("O boss já foi derrotado!");
                return;
            }
            
            
            ativarAtaqueBoss(dano+ batalha.bonusAtaque);
        }

        // ---- BUFF ----
        if (tipo === "Buff" || tipo === "buff") {
            aplicarBuff(nome);

            return;
        }

        if (tipo === "Debuff" || tipo === "debuff") {
            aplicarDebuff(nome);

            return;
        }

        if (tipo === "Controle" || tipo === "controle") {

            aplicarControle(nome);

            return;
        }
        if (tipo === "Passiva" || tipo === "passiva") {

            aplicarPassiva(nome);
            batalha.jaAgui[jogadorSelecionado] = true;
            atualizarVidaJogadores();
            atualizar_contai();

            if (batalha.jaAgui.every(v => v === true)) {
                setTimeout(() => turnoboss(), 500);
            }
            return;
        }
    }

    // ===================== INICIAR PARTIDA =====================
    async function iniciarPartida() {
        const partida = JSON.parse(localStorage.getItem("partida"));
        if (!partida) {
            alert("Partida não encontrada!");
            return;
        }

        if (partida.dificuldade == "Fácil") {
            batalha.boss.vida = 800;
            batalha.boss.vidaMax = 800;
        } else if (partida.dificuldade == "Médio") {
            batalha.boss.vida = 1200;
            batalha.boss.vidaMax = 1200;
        } else {
            batalha.boss.vida = 1400;
            batalha.boss.vidaMax = 1400;
        }

        document.body.classList.add(partida.local);
        const bossContainer = document.getElementById("boss_container");

        let imgSrc = "";
        if (partida.local === "deserto") imgSrc = "../bosses/deserto/imagem do deserto.png";
        else if (partida.local === "floresta") imgSrc = "../bosses/floresta/boss_floresta.png";
        else if (partida.local === "montanha") imgSrc = "../bosses/montanha/boss_montanha.png";
        bossContainer.innerHTML = `<img src="${imgSrc}">`;
        bossContainer.innerHTML += `
            <div class="boss_vida">
                ${criarBarraVida(batalha.boss.vida, batalha.boss.vidaMax, "boss")}
            </div>
        `;

        try {
            const response = await fetch("partida_perso_pegar.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ personagens: partida.personagens })
            });
            if (!response.ok) throw new Error("Erro ao buscar personagens");

            const personagens = await response.json();
            const containerPersonagens = document.getElementById("info");
            const containerHabilidades = document.getElementById("habilidades-list");
            containerPersonagens.innerHTML = "";
            containerHabilidades.innerHTML = "";

            personagens.forEach((p, index) => {
                const jogador = batalha.jogadores[index];
                jogador.nome = p.nome;

                p.habilidades.forEach(h => {

                    if(h.tipo === "Passiva" || h.tipo === "passiva"){

                        batalha.bonusCura += 10;

    }

});

                const div = document.createElement("div");
                div.className = "personagem";
                div.dataset.index = index;
                div.innerHTML = `
                    <img src="../${p.imagem}" alt="${p.nome}">
                    <strong>Jogador ${jogador.id} - ${p.nome}</strong>
                    ${criarBarraVida(jogador.vida, jogador.vidaMax, "player")}
                    <div style="font-size:0.7rem;color:#888;margin-top:4px;">${batalha.jaAgui[index] ? '✅ Agiu' : '⏳ Aguardando'}</div>
                `;

                div.addEventListener("click", function(e) {
                    e.stopPropagation();
                    const idx = parseInt(this.dataset.index);

                    // Se estiver em modo de seleção de alvo (cura)
                    if (modoSelecaoAlvo) {
                        aplicarCuraNoAlvo(idx, curaPendente.cura);
                        return;
                    }

                    // Se o personagem já agiu ou está morto, não permite selecionar
                    if (batalha.jaAgui[idx] || batalha.jogadores[idx].vida <= 0) {
                        alert("Este personagem não pode agir agora.");
                        return;
                    }

                    // Seleciona o personagem
                    jogadorSelecionado = idx;
                    document.querySelectorAll('.personagem').forEach(el => el.style.border = '');
                    this.style.border = '3px solid #e8e507';

                    // Lista as habilidades
                    containerHabilidades.innerHTML = "";
                    const header = document.createElement("li");
                    header.style.background = "#2b7a3e";
                    header.style.color = "white";
                    header.style.textAlign = "center";
                    header.style.fontWeight = "bold";
                    header.style.padding = "10px";
                    header.style.listStyle = "none";
                    header.style.borderRadius = "8px";
                    header.textContent = `${p.nome} - Selecione uma habilidade`;
                    containerHabilidades.appendChild(header);

                    p.habilidades.forEach(h => {
                        const li = document.createElement("li");
                        let icone = "";
                        let cor = "#ffffff";
                        if (h.tipo === "Cura" || h.tipo === "cura") {
                            icone = "[CURA] ";
                            cor = "#a8e6cf";
                        } else if (h.tipo === "Ataque" || h.tipo === "ataque") {
                            icone = "[ATAQUE] ";
                            cor = "#ff6b6b";
                        } else if (h.tipo === "Buff" || h.tipo === "buff") {
                            icone = "[BUFF] ";
                            cor = "#ffd93d";
                        }
                         else if (h.tipo === "Debuff" || h.tipo === "debuff") {
                            icone = "[DEBUFF] ";
                            cor = "#b388ff";
                        }
                        else if (h.tipo === "Controle" || h.tipo === "controle") {
                            icone = "[CONTROLE] ";
                            cor = "#4fc3f7";
                        }
                         else if (h.tipo === "Passiva" || h.tipo === "passiva") {
                            icone = "[PASSIVA] ";
                            cor = "#ff9800";
                        }

                        let texto = icone + h.nome;
                        if (h.dano > 0) texto += ` - Dano: ${h.dano}`;
                        if (h.cura > 0) texto += ` - Cura: ${h.cura}`;

                        li.textContent = texto;
                        li.style.color = cor;
                        li.style.cursor = "pointer";
                        li.style.padding = "8px 12px";
                        li.style.margin = "4px 0";
                        li.style.border = "1px solid #333";
                        li.style.borderRadius = "4px";
                        li.style.listStyle = "none";

                        li.addEventListener("mouseenter", function() {
                            this.style.background = "rgba(255,255,255,0.1)";
                        });
                        li.addEventListener("mouseleave", function() {
                            this.style.background = "transparent";
                        });

                        li.addEventListener("click", function(e) {
                            e.stopPropagation();
                            ver_ação(h.nome, h.tipo, h.dano, h.cura);
                        });
                        containerHabilidades.appendChild(li);
                    });
                });

                containerPersonagens.appendChild(div);
            });

            batalha.jaAgui = [false, false, false];
            atualizar_contai();
            atualizarVidaJogadores();
        } catch (err) {
            console.error(err);
            document.getElementById("info").innerHTML = "Erro ao carregar personagens.";
        }
    }

    iniciarPartida();

    // ===================== EVENTOS DO BOSS =====================
    const bossContainer = document.getElementById("boss_container");

    bossContainer.addEventListener("click", function(e) {
        e.stopPropagation();
        if (ataqueSelecionado && batalha.turno === "jogador") {
            // O ataque já foi contabilizado (jaAgui já foi marcado)
            batalha.boss.vida -= danoAtaque;
            atualizarVidaBoss();
            // vai contar a ação do personagen so se clicar no container do boss, se clicar fora ele cancela o ataque
            batalha.jaAgui[jogadorSelecionado] = true;
            atualizarVidaJogadores();
            atualizar_contai();

            if (batalha.boss.vida <= 0) return;

            ataqueSelecionado = false;
            danoAtaque = 0;
            this.classList.remove("ataque_ativo");

            // Verifica se todos já agiram
            if (batalha.jaAgui.every(v => v === true)) {
                setTimeout(() => turnoboss(), 500);
            } else {
                // Ainda há personagens para agir
                document.getElementById('habilidades-list').innerHTML = '';
                // Limpa seleção do personagem atual
                jogadorSelecionado = -1;
                document.querySelectorAll('.personagem').forEach(el => el.style.border = '');
                // Mostra mensagem para escolher próximo
                const container = document.getElementById('habilidades-list');
                container.innerHTML = `
                    <li style="background:#2b7a3e;color:#e8e507;text-align:center;padding:10px;list-style:none;border-radius:8px;">
                        Escolha o próximo personagem para agir
                    </li>
                `;
            }
        }
    });

    document.addEventListener("click", function(e) {
        if (!ataqueSelecionado) return;
        if (!e.target.closest("#boss_container")) {
            ataqueSelecionado = false;
            danoAtaque = 0;
            bossContainer.classList.remove("ataque_ativo");
            document.getElementById('habilidades-list').innerHTML = '';
        }
    });

    document.getElementById("fechar_ataque").addEventListener("click", function() {
        document.getElementById("ataque_boss_container").style.display = "none";
        batalha.turno = "jogador";
        atualizar_contai();
    });
</script>
</body>
</html>