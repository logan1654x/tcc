<?php

class Personagem {

    private int    $id;
    private string $nome;
    private string $classe;
    private string $aspecto;
    private int    $usuarioId;
    private ?string $caminhoImagem; // Agora guarda o caminho do arquivo

    public function __construct(array $dados) {
        $this->id           = (int) ($dados['id']           ?? 0);
        $this->nome         = $dados['nome']         ?? '';
        $this->classe       = $dados['classe']       ?? '';
        $this->aspecto      = $dados['aspecto']      ?? '';
        $this->usuarioId    = (int) ($dados['usuario_id']   ?? 0);
        $this->caminhoImagem = $dados['caminho_imagem'] ?? $dados['imagem'] ?? null; 
    }

    public function getId(): int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getClasse(): string { return $this->classe; }
    public function getAspecto(): string { return $this->aspecto; }
    public function getUsuarioId(): int { return $this->usuarioId; }
    public function getCaminhoImagem(): ?string { return $this->caminhoImagem; }
    
    // Método para obter a URL completa da imagem
    public function getImagemUrl(): ?string {
        if ($this->caminhoImagem) {
            return '/Trab_Lp3/' . $this->caminhoImagem;
        }
        return null;
    }

    public static function novo(string $nome, string $classe, string $aspecto, int $usuarioId, ?string $caminhoImagem = null): Personagem {
        if ($usuarioId <= 0) {
            throw new InvalidArgumentException('Usuário inválido.');
        }

        $personagem = new Personagem(['usuario_id' => $usuarioId]);
        $personagem->alterarDados($nome, $classe, $aspecto, $caminhoImagem);

        return $personagem;
    }

    public function alterarDados(string $nome, string $classe, string $aspecto, ?string $caminhoImagem = null): void {
        $nome       = trim($nome);
        $classe     = trim($classe);
        $aspecto    = trim($aspecto);

        if ($nome === '' || $classe === '' || $aspecto === '') {
            throw new InvalidArgumentException('Nome, classe e aspecto são obrigatórios.');
        }

        $this->nome    = $nome;
        $this->classe  = $classe;
        $this->aspecto = $aspecto;
        
        // Se um novo caminho foi fornecido, atualiza
        if ($caminhoImagem !== null) {
            $this->caminhoImagem = $caminhoImagem;
        }
    }
    
    // Método para remover a imagem
    public function removerImagem(): void {
        // Deletar o arquivo físico se existir
        if ($this->caminhoImagem && file_exists(__DIR__ . '/../' . $this->caminhoImagem)) {
            unlink(__DIR__ . '/../' . $this->caminhoImagem);
        }
        $this->caminhoImagem = null;
    }

    public function registrarIdGerado(int $id): void {
        if ($id <= 0) {
            throw new InvalidArgumentException('ID inválido.');
        }
        $this->id = $id;
    }
}