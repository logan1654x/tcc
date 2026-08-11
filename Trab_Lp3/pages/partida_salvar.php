<?php

require_once __DIR__ . '/../config/database.php';

$dados = json_decode(
    file_get_contents("php://input"),
    true
);

$conexao = getConexao();

$dif = $dados['dificuldade'];
$local = $dados['local'];

/* cria a partida */
$stmt = $conexao->prepare("
    INSERT INTO partida (dif, local)
    VALUES (?, ?)
");

$stmt->execute([
    $dif,
    $local
]);

/* pega o ID gerado */
$idPartida = $conexao->lastInsertId();

/* relaciona os personagens */
$stmt = $conexao->prepare("
    INSERT INTO partida_personagem
    (ID_partida, ID_personagem)
    VALUES (?, ?)
");

foreach ($dados['personagens'] as $idPersonagem) {

    $stmt->execute([
        $idPartida,
        $idPersonagem
    ]);
}

echo json_encode([
    "sucesso" => true,
    "id_partida" => $idPartida
]);