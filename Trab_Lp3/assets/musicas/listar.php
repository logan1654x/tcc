<?php
// Lista todas as músicas da pasta
$pasta = __DIR__;
$arquivos = glob($pasta . '/*.mp3');

$musicas = [];
foreach ($arquivos as $arquivo) {
    $musicas[] = [
        'nome' => basename($arquivo),
        'caminho' => '../assets/musicas/' . basename($arquivo)
    ];
}

// Retorna como JSON
header('Content-Type: application/json');
echo json_encode($musicas);
?>