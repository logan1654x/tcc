<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CRUDspact</title>
  <link rel="stylesheet" href="../assets/style.css" />
  <link rel="icon" type="image/x-icon" href="../assets/gate.ico">
</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <a href="../pages/index.php" class="logo">CRUDspact</a>

    <nav class="nav">
      <a href="../pages/index.php">Meus Personagens</a>
      <a href="../pages/personagem_create.php">+ Novo personagem</a>
    </nav>

    <!-- Player com playlist -->
    <div class="audio-player">
      <audio id="bgAudio" preload="auto" loop>
        <source src="" type="audio/mpeg">
      </audio>
      <div class="player-controls">
        <button id="prevBtn" class="player-btn">⏮</button>
        <button id="playPauseBtn" class="player-btn">▶ PLAY</button>
        <button id="nextBtn" class="player-btn">⏭</button>
        <span id="audioStatus" class="audio-status">🔇 Carregando playlist...</span>
      </div>
    </div>

    <div class="header-user">
      <?php
        $nomeUser = $_SESSION['usuario_nome'] ?? 'Usuário';
        $fotoPerfil = '';
        
        if (isset($_SESSION['usuario_id'])) {
            require_once __DIR__ . '/../repository/UsuarioRepository.php';
            $repoUser = new UsuarioRepository();
            $user = $repoUser->buscarPorId($_SESSION['usuario_id']);
            if ($user && $user->getFotoPerfil()) {
                $fotoPerfil = '/Trab_Lp3/' . $user->getFotoPerfil();
              }
          }
        ?>
        
        <a href="usuario_perfil.php" class="foto-perfil-link">
          <?php if ($fotoPerfil && file_exists(__DIR__ . '/..' . str_replace('/Trab_Lp3', '', $fotoPerfil))): ?>
            <img src="<?= $fotoPerfil ?>" alt="Perfil" class="foto-perfil-mini">
          <?php else: ?>
            <div class="foto-perfil-placeholder">👤</div>
          <?php endif; ?>
        </a>
        
        <span class="user-name">
          <?= htmlspecialchars($nomeUser) ?>
        </span>
        <a href="../pages/logout.php" class="btn-logout">Sair</a>
      </div>
    </div>
  </header>

  <main class="container">

<script>
(function() {
    const audio = document.getElementById('bgAudio');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const status = document.getElementById('audioStatus');
    
    let playlist = [];
    let musicaAtual = 0;
    let tocando = false;
    let nomeMusicaAtual = '';
    
    // Carregar playlist do servidor
    function carregarPlaylist() {
        fetch('../assets/musicas/listar.php')
            .then(response => response.json())
            .then(data => {
                playlist = data;
                if (playlist.length > 0) {
                    status.innerHTML = `${playlist.length} músicas carregadas`;
                    carregarMusica(0);
                } else {
                    status.innerHTML = 'Nenhuma música encontrada';
                }
            })
            .catch(error => {
                console.error('Erro ao carregar playlist:', error);
                status.innerHTML = 'Erro ao carregar músicas';
            });
    }
    
    // Atualizar texto do status
    function atualizarStatus() {
        if (tocando) {
            status.innerHTML = `🎵 TOCANDO: ${nomeMusicaAtual}`;
            playPauseBtn.innerHTML = '⏸ PAUSE';
        } else {
            status.innerHTML = `🔇 PAUSADO: ${nomeMusicaAtual}`;
            playPauseBtn.innerHTML = '▶ PLAY';
        }
    }
    
    // Carregar uma música específica
    function carregarMusica(index) {
        if (playlist.length === 0) return;
        
        musicaAtual = index;
        const musica = playlist[musicaAtual];
        audio.src = musica.caminho;
        audio.load();
        
        // Mostrar nome da música (sem a extensão)
        nomeMusicaAtual = musica.nome.replace('.mp3', '').replace(/_/g, ' ');
        
        atualizarStatus();
        
        if (tocando) {
            audio.play();
        }
    }
    
    // Tocar música
    function tocarMusica() {
        if (playlist.length === 0) return;
        
        audio.play();
        tocando = true;
        atualizarStatus();
        
        // Salvar estado
        sessionStorage.setItem('audioPlaying', 'true');
        sessionStorage.setItem('musicaAtual', musicaAtual);
        sessionStorage.setItem('playlist', JSON.stringify(playlist.map(m => m.caminho)));
        sessionStorage.setItem('nomeMusica', nomeMusicaAtual);
    }
    
    // Pausar música
    function pausarMusica() {
        audio.pause();
        tocando = false;
        atualizarStatus();
        sessionStorage.setItem('audioPlaying', 'false');
    }
    
    // Próxima música
    function proximaMusica() {
        if (playlist.length === 0) return;
        
        musicaAtual = (musicaAtual + 1) % playlist.length;
        carregarMusica(musicaAtual);
        if (tocando) {
            audio.play();
        }
    }
    
    // Música anterior
    function musicaAnterior() {
        if (playlist.length === 0) return;
        
        musicaAtual = (musicaAtual - 1 + playlist.length) % playlist.length;
        carregarMusica(musicaAtual);
        if (tocando) {
            audio.play();
        }
    }
    
    // Quando a música terminar, tocar a próxima
    audio.addEventListener('ended', function() {
        proximaMusica();
    });
    
    // Recuperar estado anterior (para continuar entre páginas)
    function recuperarEstado() {
        const savedPlaying = sessionStorage.getItem('audioPlaying');
        const savedMusica = sessionStorage.getItem('musicaAtual');
        const savedPlaylist = sessionStorage.getItem('playlist');
        const savedTime = sessionStorage.getItem('audioTime');
        const savedNome = sessionStorage.getItem('nomeMusica');
        
        if (savedPlaylist) {
            try {
                const caminhos = JSON.parse(savedPlaylist);
                if (caminhos.length > 0) {
                    playlist = caminhos.map(caminho => ({ nome: caminho.split('/').pop(), caminho: caminho }));
                    musicaAtual = parseInt(savedMusica) || 0;
                    nomeMusicaAtual = savedNome || playlist[musicaAtual].nome.replace('.mp3', '').replace(/_/g, ' ');
                    
                    const musica = playlist[musicaAtual];
                    audio.src = musica.caminho;
                    audio.load();
                    
                    // Restaurar o tempo da música
                    if (savedTime) {
                        audio.currentTime = parseFloat(savedTime);
                    }
                    
                    atualizarStatus();
                    
                    if (savedPlaying === 'true') {
                        setTimeout(() => {
                            audio.play().then(() => {
                                tocando = true;
                                atualizarStatus();
                            }).catch(() => {
                                tocando = false;
                                atualizarStatus();
                            });
                        }, 300);
                    }
                    return;
                }
            } catch(e) {
                console.error('Erro ao recuperar estado:', e);
            }
        }
        
        // Se não tem estado salvo, carrega playlist
        carregarPlaylist();
    }
    
    // Salvar estado antes de sair da página
    function salvarEstado() {
        sessionStorage.setItem('audioTime', audio.currentTime);
        sessionStorage.setItem('audioPlaying', tocando);
        sessionStorage.setItem('musicaAtual', musicaAtual);
        sessionStorage.setItem('nomeMusica', nomeMusicaAtual);
        if (playlist.length > 0) {
            sessionStorage.setItem('playlist', JSON.stringify(playlist.map(m => m.caminho)));
        }
    }
    
    // Eventos dos botões
    playPauseBtn.onclick = function() {
        if (playlist.length === 0) return;
        
        if (tocando) {
            pausarMusica();
        } else {
            tocarMusica();
        }
    };
    
    nextBtn.onclick = function() {
        proximaMusica();
    };
    
    prevBtn.onclick = function() {
        musicaAnterior();
    };
    
    // Salvar antes de sair
    window.addEventListener('beforeunload', salvarEstado);
    
    // Iniciar
    audio.volume = 0.5;
    recuperarEstado();
})();
</script> 