<?php
    require_once __DIR__ . '/../config/database.php';
class Habilidades {
private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorOrigem(string $origem): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM habilidades WHERE origem = :origem'
        );

        $stmt->execute([
            ':origem' => $origem
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function associarAoPersonagem(
        int $personagemId,
        int $habilidadeId
    ): void {

        $stmt = $this->pdo->prepare(
            'INSERT INTO personagem_habilidade
            (personagem_id, habilidade_id)
            VALUES (:personagem, :habilidade)'
        );

        $stmt->execute([
            ':personagem' => $personagemId,
            ':habilidade' => $habilidadeId
        ]);
    }
}
 
    