<?php
require_once __DIR__ . '\\..\\config\\database.php';
$pdo = getConexao();

// Recebe os IDs do JS
$json = file_get_contents('php://input');
$dados = json_decode($json, true);
$ids = $dados['personagens'] ?? [];

// Preparar placeholders
if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    // 1️⃣ Busca personagens
    $sql = "SELECT id, nome, imagem FROM personagem WHERE id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($ids);
    $personagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2️⃣ Busca habilidades (com DANO e CURA)
    $sql2 = "
        SELECT ph.personagem_id, h.id AS habilidade_id, h.nome, h.dano, h.cura, h.tipo
        FROM personagem_habilidade ph
        JOIN habilidades h ON ph.habilidade_id = h.id
        WHERE ph.personagem_id IN ($placeholders)
    ";
    $stmt = $pdo->prepare($sql2);
    $stmt->execute($ids);
    $habilidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3️⃣ Associa habilidades aos personagens
    foreach ($personagens as &$p) {
        $p['habilidades'] = array_filter($habilidades, function($h) use ($p) {
            return $h['personagem_id'] == $p['id'];
        });
        // opcional: reindexar array
        $p['habilidades'] = array_values($p['habilidades']);
    }

} else {
    $personagens = [];
}

// Retorna JSON
header('Content-Type: application/json');
echo json_encode($personagens);
?>