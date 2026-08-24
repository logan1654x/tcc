<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$dados = json_decode(
    file_get_contents("php://input"),
    true
);

$conexao = getConexao();

$usuario_id = $_SESSION['usuario_id'];
$save = $dados['save'];
$dif = $dados['dificuldade'];

/* cria a partida */
$stmt = $conexao->prepare("
    INSERT INTO partida (usuario_id, save, dif)
    VALUES (?, ?, ?)
");

$stmt->execute([
    $usuario_id,
    $save,
    $dif
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